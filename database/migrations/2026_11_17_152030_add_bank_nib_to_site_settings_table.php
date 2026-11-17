<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('site_settings', 'bank_nib')) {
            return;
        }

        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('bank_nib', 32)->nullable()->after('bank_nif');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('site_settings', 'bank_nib')) {
            return;
        }

        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn('bank_nib');
        });
    }
};
