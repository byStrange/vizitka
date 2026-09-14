<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'slug' => 'vorotniki-i-manzheti',
                'name' => ['ru' => 'Воротники и манжеты', 'uz' => 'Yoqa va manjeta'],
                'description' => ['ru' => 'Плосковязаные воротники и манжеты для рубашек, блуз, верхней одежды.', 'uz' => "Ko'ylaklar, bluzlar va tashqi kiyimlar uchun tekis trikotaj yoqalar va manjetalar."],
                'sort_order' => 1,
            ],
            [
                'slug' => 'lenty-i-tesmy',
                'name' => ['ru' => 'Ленты и тесьмы', 'uz' => 'Lentalar va tasma'],
                'description' => ['ru' => 'Вязанные, тканые, жаккардовые и эластичные ленты для одежды и сувениров.', 'uz' => "Kiyim va suvenirlari uchun trikotaj, to'quv, jakkard va elastik lentalar."],
                'sort_order' => 2,
            ],
            [
                'slug' => 'shnury',
                'name' => ['ru' => 'Шнуры', 'uz' => 'Arqonlar'],
                'description' => ['ru' => 'Кругловязаные, декоративные и плетельные шнуры. 6-ти игольные — наше уникальное предложение.', 'uz' => "Dumaloq trikotaj, bezak va to'quv arqonlar. 6-ishli arqonlar — bizning noyob taklifimiz."],
                'sort_order' => 3,
            ],
            [
                'slug' => 'vyazannye-izdeliya',
                'name' => ['ru' => 'Вязанные изделия', 'uz' => 'Trikotaj buyumlar'],
                'description' => ['ru' => 'Готовые трикотажные изделия и шарфы: мужские, женские, детские.', 'uz' => 'Tayyor trikoj buyumlar va sharfilar: erkaklar, ayollar, bolalar uchun.'],
                'sort_order' => 4,
            ],
            [
                'slug' => 'fasonnaya-pryazha',
                'name' => ['ru' => 'Фасонная пряжа', 'uz' => 'Fason ip'],
                'description' => ['ru' => 'Фасонные нити для ручного и машинного вязания. Широкая палитра цветов.', 'uz' => "Qo'lda va mashinada trikoj uchun fason ip. Keng rang palitrasi."],
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
