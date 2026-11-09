<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

/**
 * منتجات تجريبية لمتجر Alo. تقدر تعدلها أو تحذفها من لوحة التحكم.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /** @var array<string, array{name: string, products: list<array{0: string, 1: int, 2: int|null, 3: string}>}> $catalog */
        $catalog = [
            'phone-cases' => [
                'name' => 'كفرات الجوال',
                'products' => [
                    ['كفر الخريطة القديمة', 89, 119, 'كفر جلد بطبعة خريطة عتيقة، يحمي الجوال ويعطيه طابع الرحّالة.'],
                    ['كفر ليلة النجوم', 79, null, 'كفر سيليكون مطفي بنقشة سماء ليلية ونجوم ذهبية.'],
                    ['كفر الزخرفة الأندلسية', 95, null, 'نقوش هندسية أندلسية بلون عاجي، مقاوم للخدوش.'],
                    ['كفر جلد بني كلاسيك', 110, 140, 'جلد صناعي فاخر بلون بني دافي مع حافة مرتفعة للكاميرا.'],
                ],
            ],
            'metal-stickers' => [
                'name' => 'ستيكرات معدنية',
                'products' => [
                    ['ستيكر القمر والنجمة', 25, null, 'ستيكر معدني بطلاء مينا، يناسب الجوال واللابتوب.'],
                    ['ستيكر الفنجان', 25, 35, 'فنجان قهوة صغير بتفاصيل ذهبية.'],
                    ['مجموعة ستيكرات الرحلة', 60, null, 'خمسة ستيكرات معدنية: بوصلة، خيمة، جبل، نخلة، وطائرة.'],
                ],
            ],
            'bookmarks' => [
                'name' => 'فواصل الكتب',
                'products' => [
                    ['فاصل النجمة النحاسي', 45, 65, 'فاصل نحاسي مصقول بنجمة مفرغة وشريط مخملي.'],
                    ['فاصل الريشة', 39, null, 'فاصل معدني على شكل ريشة كاتب.'],
                    ['فاصل اقتباس مخصص', 55, null, 'فاصل نحاسي ينحت عليه اقتباسك المفضل.'],
                ],
            ],
        ];

        $sortOrder = 0;

        foreach ($catalog as $slug => $section) {
            $category = Category::query()->updateOrCreate(
                ['slug' => $slug],
                ['name' => $section['name'], 'sort_order' => $sortOrder++],
            );

            foreach ($section['products'] as $index => [$name, $price, $compareAtPrice, $description]) {
                Product::query()->updateOrCreate(
                    ['slug' => $slug.'-'.($index + 1)],
                    [
                        'category_id' => $category->id,
                        'name' => $name,
                        'description' => $description,
                        'price' => $price,
                        'compare_at_price' => $compareAtPrice,
                        'stock' => 20,
                        'is_featured' => $index < 2,
                        'is_active' => true,
                    ],
                );
            }
        }
    }
}
