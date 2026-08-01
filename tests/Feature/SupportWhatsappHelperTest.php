<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Verifies the shared WhatsApp support helpers used by the crypto deposit
 * help card, so the support deep link always points at the real number and
 * prefilled messages are URL-safe.
 */
class SupportWhatsappHelperTest extends TestCase
{
    public function test_support_whatsapp_number_is_digits_only()
    {
        $this->assertSame('447464483316', support_whatsapp_number());
        $this->assertMatchesRegularExpression('/^[0-9]+$/', support_whatsapp_number());
    }

    public function test_support_whatsapp_link_without_message()
    {
        $this->assertSame('https://wa.me/447464483316', support_whatsapp_link());
        $this->assertSame('https://wa.me/447464483316', support_whatsapp_link(''));
    }

    public function test_support_whatsapp_link_prefills_message()
    {
        $link = support_whatsapp_link('Need help with a $250.00 deposit?');
        $this->assertStringStartsWith('https://wa.me/447464483316?text=', $link);
        $this->assertStringContainsString(rawurlencode('Need help with a $250.00 deposit?'), $link);
    }
}
