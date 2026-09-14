<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'slug' => 'vorotnik-6',
                'name' => ['ru' => 'Воротник-6', 'uz' => 'Yoqa-6'],
                'sku' => 'DK-0001',
                'category_id' => 1,
                'description' => ['ru' => 'Плосковязаный воротник для рубашек и блуз.', 'uz' => "Ko'ylaklar va bluzlar uchun tekis trikotaj yoqa."],
                'specs' => [
                    ['label' => 'Состав', 'value' => '100% хлопок'],
                    ['label' => 'Ширина', 'value' => '3.5 см'],
                ],
                'moq' => '500 шт',
                'lead_time' => '7-14 дней',
                'customization' => true,
                'is_new' => true,
                'is_active' => true,
            ],
            [
                'slug' => 'vorotnik-5',
                'name' => ['ru' => 'Воротник-5', 'uz' => 'Yoqa-5'],
                'sku' => 'DK-0002',
                'category_id' => 1,
                'description' => ['ru' => 'Плосковязаный воротник с фигурным краем.', 'uz' => 'Figurali chetli tekis trikotaj yoqa.'],
                'specs' => [
                    ['label' => 'Состав', 'value' => '100% хлопок'],
                    ['label' => 'Ширина', 'value' => '4.0 см'],
                ],
                'moq' => '500 шт',
                'lead_time' => '7-14 дней',
                'customization' => true,
                'is_new' => true,
                'is_active' => true,
            ],
            [
                'slug' => 'shnur-kruglovyazanyy',
                'name' => ['ru' => 'Шнур кругловязаный 6-ти игольный', 'uz' => '6-ishli dumaloq trikoj arqon'],
                'sku' => 'DK-0030',
                'category_id' => 3,
                'description' => ['ru' => 'Уникальный 6-ти игольный кругловязаный шнур.', 'uz' => 'Noyob 6-ishli dumaloq trikoj arqon.'],
                'specs' => [
                    ['label' => 'Состав', 'value' => '100% хлопок / полиэстер'],
                    ['label' => 'Диаметр', 'value' => '3-8 мм'],
                ],
                'moq' => '500 м',
                'lead_time' => '10-14 дней',
                'customization' => true,
                'is_new' => true,
                'discount_percent' => 3,
                'is_active' => true,
            ],
            [
                'slug' => 'manzhet-v-polosku',
                'name' => ['ru' => 'Манжет в полоску', 'uz' => "Yolg'izli manjeta"],
                'sku' => 'DK-0010',
                'category_id' => 1,
                'description' => ['ru' => 'Декоративный манжет в полоску.', 'uz' => "Bezakli yolg'izli manjeta."],
                'specs' => [
                    ['label' => 'Состав', 'value' => '100% полиэстер'],
                    ['label' => 'Ширина', 'value' => '5.0 см'],
                ],
                'moq' => '300 шт',
                'lead_time' => '5-10 дней',
                'customization' => false,
                'discount_percent' => 20,
                'is_active' => true,
            ],
            [
                'slug' => 'lenta-zhakkardovaya',
                'name' => ['ru' => 'Лента жаккардовая с логотипом', 'uz' => 'Jakkard lentasi logotip bilan'],
                'sku' => 'DK-0020',
                'category_id' => 2,
                'description' => ['ru' => 'Жаккардовая лента с интегрированным логотипом.', 'uz' => 'Integrallashgan logotip bilan jakkard lentasi.'],
                'specs' => [
                    ['label' => 'Состав', 'value' => '100% полиэстер'],
                    ['label' => 'Ширина', 'value' => '10-25 мм'],
                ],
                'moq' => '1000 м',
                'lead_time' => '14-21 день',
                'customization' => true,
                'is_new' => true,
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
