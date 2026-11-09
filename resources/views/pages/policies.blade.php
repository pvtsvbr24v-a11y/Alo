<x-layouts.store title="الشحن والاستبدال والاسترجاع">
    <article class="mx-auto max-w-2xl space-y-6 leading-8">
        <h1 class="squiggle text-4xl">الشحن والاستبدال والاسترجاع</h1>
        <section>
            <h2 class="text-xl font-bold">الشحن</h2>
            <p>نشحن لكل مدن المملكة خلال ٢ إلى ٥ أيام عمل. رسوم الشحن <x-price :amount="config('store.shipping_fee')" />، ومجاني للطلبات فوق <x-price :amount="config('store.free_shipping_from')" />.</p>
        </section>
        <section>
            <h2 class="text-xl font-bold">الاستبدال والاسترجاع</h2>
            <p>تقدر تستبدل أو ترجع المنتج خلال ٧ أيام من الاستلام بشرط يكون بحالته الأصلية. المنتجات المخصصة (مثل التشارمز بالاسم) ما تُسترجع إلا إذا فيها عيب مصنعي.</p>
        </section>
        <p class="text-sm text-muted">(راجع هذي السياسات وعدّلها حسب طريقة شغلك قبل الإطلاق)</p>
    </article>
</x-layouts.store>
