<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->renameColumn('style_footer_logo', 'style_footer_logo_ar');
            $table->text('style_footer_logo_en')->nullable()->after('style_footer_logo_ar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('style_footer_logo_en');
            $table->renameColumn('style_footer_logo_ar', 'style_footer_logo');
        });
    }
};
