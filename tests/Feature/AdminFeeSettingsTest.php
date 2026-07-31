<?php

namespace Tests\Feature;

use App\Models\Admin\Admin;
use App\Models\Admin\TransactionSetting;
use Tests\TestCase;

class AdminFeeSettingsTest extends TestCase
{
    public function test_admin_can_update_virtual_card_fee()
    {
        $admin = Admin::find(1);
        $this->assertNotNull($admin, 'super admin (id=1) must exist to edit fees');
        $this->actingAs($admin, 'admin');

        $page = $this->get(route('admin.trx.settings.index'));
        $page->assertStatus(200);
        $page->assertSee('Virtual Card Charges');

        $setting = TransactionSetting::where('slug', 'virtual_card')->first();
        $this->assertNotNull($setting, 'virtual_card charge row must exist (run TransactionSettingSeeder)');
        $originalMin = (float) $setting->min_limit;

        try {
            $slug = 'virtual_card';
            $response = $this->put(route('admin.trx.settings.charges.update'), [
                'slug' => $slug,
                $slug.'_fixed_charge' => 1.00,
                $slug.'_percent_charge' => 1.00,
                $slug.'_min_limit' => 150.00,
                $slug.'_max_limit' => 50000.00,
                $slug.'_daily_limit' => 50000.00,
                $slug.'_monthly_limit' => 50000.00,
            ]);
            $response->assertSessionHas('success');

            $updated = TransactionSetting::where('slug', $slug)->first();
            $this->assertEquals(150.00, (float) $updated->min_limit);
            $this->assertEquals(150.00, get_virtual_card_fee());
        } finally {
            $setting->min_limit = $originalMin;
            $setting->save();
        }
    }
}
