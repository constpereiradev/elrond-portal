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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('council_id')->nullable();
            $table->foreign('council_id')->references('id')->on('councils')->onDelete('restrict');

            $table->unsignedBigInteger('kingdom_id')->nullable();
            $table->foreign('kingdom_id')->references('id')->on('kingdoms')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['council_id']);
            $table->dropForeign(['kingdom_id']);

            $table->dropColumn('council_id');
            $table->dropColumn('kingdom_id');
        });
    }
};
