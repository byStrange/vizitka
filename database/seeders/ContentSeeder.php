<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        Page::create([
            'slug' => 'about',
            'title' => ['ru' => 'О компании', 'uz' => 'Kompaniya haqida'],
            'content' => ['ru' => 'Mirum Textile — производитель текстильных аксессуаров...', 'uz' => 'Mirum Textile — tekstil aksessuarlari ishlab chiqaruvchisi...'],
            'meta_title' => 'О компании — Mirum Textile',
            'meta_description' => 'Производитель текстильных аксессуаров с 2001 года.',
            'is_active' => true,
        ]);

        $settings = [
            ['key' => 'phone', 'value' => ['ru' => '+(99871) 253 95 40', 'uz' => '+(99871) 253 95 40'], 'group' => 'contacts', 'type' => 'phone'],
            ['key' => 'email', 'value' => ['ru' => 'info@mirumtextile.uz', 'uz' => 'info@mirumtextile.uz'], 'group' => 'contacts', 'type' => 'email'],
            ['key' => 'address', 'value' => ['ru' => 'г. Ташкент, Узбекистан', 'uz' => "Toshkent shahri, O'zbekiston"], 'group' => 'contacts', 'type' => 'text'],
            ['key' => 'working_hours', 'value' => ['ru' => 'Пн–Пт, 09:00–18:00', 'uz' => 'Du–Ju, 09:00–18:00'], 'group' => 'contacts', 'type' => 'text'],
            ['key' => 'brand_name', 'value' => ['ru' => 'Mirum Textile', 'uz' => 'Mirum Textile'], 'group' => 'general', 'type' => 'text'],
            ['key' => 'copyright', 'value' => ['ru' => '© 2001—2026 «Mirum Textile»', 'uz' => '© 2001—2026 «Mirum Textile»'], 'group' => 'general', 'type' => 'text'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
