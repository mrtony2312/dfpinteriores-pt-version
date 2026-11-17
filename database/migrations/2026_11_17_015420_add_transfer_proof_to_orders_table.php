<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'transfer_proof_path')) {
                $table->string('transfer_proof_path')->nullable()->after('items_snapshot');
            }
            if (! Schema::hasColumn('orders', 'transfer_proof_name')) {
                $table->string('transfer_proof_name')->nullable()->after('transfer_proof_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $cols = array_values(array_filter([
                Schema::hasColumn('orders', 'transfer_proof_path') ? 'transfer_proof_path' : null,
                Schema::hasColumn('orders', 'transfer_proof_name') ? 'transfer_proof_name' : null,
            ]));

            if ($cols !== []) {
                $table->dropColumn($cols);
            }
        });
    }
};
