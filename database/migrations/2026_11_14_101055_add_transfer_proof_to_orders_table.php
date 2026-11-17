<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('transfer_proof_path')->nullable()->after('items_snapshot');
            $table->string('transfer_proof_name')->nullable()->after('transfer_proof_path');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['transfer_proof_path', 'transfer_proof_name']);
        });
    }
};
