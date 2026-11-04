<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('webmaster_sections', function (Blueprint $table) {
            $table->boolean('source_status')->default(false);
        });
        Schema::table('topics', function (Blueprint $table) {
            $table->string('source')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('topics', fn (Blueprint $table) => $table->dropColumn('source'));
        Schema::table('webmaster_sections', fn (Blueprint $table) => $table->dropColumn('source_status'));
    }
};
