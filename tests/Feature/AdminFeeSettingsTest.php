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

        // Use a dedicated row so the real 'virtual_card' fee is never touched.
        $slug = 'test_fee_'.substr(md5(uniqid()), 0, 8);
        $row = TransactionSetting::forceCreate([
            'admin_id' => 1,
            'slug' => $slug,
            'title' => 'Test Virtual Card Charges',
            'fixed_charge' => 1.00,
            'percent_charge' => 1.00,
            'min_limit' => 100.00,
            'max_limit' => 50000.00,
            'monthly_limit' => 50000.00,
            'daily_limit' => 50000.00,
        ]);

        try {
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
        } finally {
            TransactionSetting::where('slug', $slug)->delete();
        }
    }
}
