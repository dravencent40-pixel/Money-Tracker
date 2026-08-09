<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->index('user_id');
        });

        $userId = DB::table('users')->orderBy('id')->value('id');
        if ($userId !== null) {
            DB::table('budgets')->whereNull('user_id')->update(['user_id' => $userId]);
        }

        Schema::table('budgets', function (Blueprint $table) {
            $table->dropUnique('budgets_category_id_month_unique');
        });

        Schema::table('budgets', function (Blueprint $table) {
            $table->unique(['user_id', 'category_id', 'month']);
        });
    }

    public function down(): void
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->dropUnique('budgets_user_id_category_id_month_unique');
            $table->dropForeign(['user_id']);
            $table->dropIndex(['budgets_user_id_index']);
            $table->dropColumn('user_id');
        });

        Schema::table('budgets', function (Blueprint $table) {
            $table->unique(['category_id', 'month']);
        });
    }
};
