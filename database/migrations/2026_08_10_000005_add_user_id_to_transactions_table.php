<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->index('user_id');
        });

        $userId = DB::table('users')->orderBy('id')->value('id');
        if ($userId !== null) {
            DB::table('transactions')->whereNull('user_id')->update(['user_id' => $userId]);
        }
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex(['transactions_user_id_index']);
            $table->dropColumn('user_id');
        });
    }
};
