<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->index('user_id');
        });

        $userId = DB::table('users')->orderBy('id')->value('id');
        if ($userId !== null) {
            DB::table('categories')->whereNull('user_id')->update(['user_id' => $userId]);
        }

        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique('categories_name_type_unique');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->unique(['user_id', 'name', 'type']);
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique('categories_user_id_name_type_unique');
            $table->dropForeign(['user_id']);
            $table->dropIndex(['categories_user_id_index']);
            $table->dropColumn('user_id');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->unique(['name', 'type']);
        });
    }
};
