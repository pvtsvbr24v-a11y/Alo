<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * صفحة العميل: الأفتار (صورة data URI أو رمز جاهز) والعنوان المحفوظ.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->longText('avatar')->nullable()->after('name');
            $table->string('city')->nullable()->after('avatar');
            $table->string('district')->nullable()->after('city');
            $table->string('address')->nullable()->after('district');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'city', 'district', 'address']);
        });
    }
};
