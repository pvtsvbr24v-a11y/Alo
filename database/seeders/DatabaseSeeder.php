<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

/**
 * منتجات تجريبية لمتجر Alo (ستيكرات وتشارمز). تقدر تعدلها أو تحذفها من لوحة التحكم.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /** @var array<string, array{name: string, products: list<array{0: string, 1: int, 2: int|null, 3: string}>}> $catalog */
        $catalog = [
            'stickers' => [
                'name' => 'ستيكرات',
                'products' => [
                    ['ستيكر القطة السوداء', 15, null, 'قطة سوداء بعيون ذهبية تطل من زاوية جوالك أو دفترك. ستيكر فينيل مقاوم للماء.'],
                    ['ستيكر القمر والنجمة', 15, 20, 'هلال ونجمة بلمسة ذهبية، لكل من يحب السهر مع الحكايات.'],
                    ['ستيكر الفنجان', 12, null, 'فنجان قهوة صغير بتفاصيل دافية، يناسب اللابتوب والمطارة.'],
                    ['مجموعة ستيكرات الحكاية', 45, 60, 'خمسة ستيكرات: كتاب مفتوح، قطة، قمر، فنجان، ونجمة.'],
                ],
            ],
            'charms' => [
                'name' => 'تشارمز',
                'products' => [
                    ['تشارم القطة الصغيرة', 35, null, 'تشارم معدني بقطة سوداء صغيرة، يتعلق في الجوال أو الشنطة.'],
                    ['تشارم النجمة الذهبية', 39, 49, 'نجمة ذهبية لامعة مع خرز سماوي ناعم.'],
                    ['تشارم الحرف بالاسم', 45, null, 'تشارم بحرف اسمك أو اسم أحد تحبه، مع خرز باستيل.'],
                    ['تشارم الفيونكة', 29, null, 'فيونكة وردية ناعمة مع لؤلؤة صغيرة.'],
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
