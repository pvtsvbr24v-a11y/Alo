<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * أقسام المتجر: Stickers و Charms و Bookmarks (تنضاف أو تتعدل لحالها مع النشر).
     */
    public function up(): void
    {
        foreach ([['stickers', 'Stickers', 0], ['charms', 'Charms', 1], ['bookmarks', 'Bookmarks', 2]] as [$slug, $name, $order]) {
            $exists = DB::table('categories')->where('slug', $slug)->exists();

            if ($exists) {
                DB::table('categories')->where('slug', $slug)->update(['name' => $name, 'sort_order' => $order, 'updated_at' => now()]);
            } else {
                DB::table('categories')->insert(['slug' => $slug, 'name' => $name, 'sort_order' => $order, 'created_at' => now(), 'updated_at' => now()]);
            }
        }
    }

    public function down(): void
    {
        //
    }
};
