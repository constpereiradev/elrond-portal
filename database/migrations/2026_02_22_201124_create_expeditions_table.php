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
        Schema::create('expeditions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kingdom_id')->required()->comment('Reino que está criando a expedição.');
            $table->foreign('kingdom_id')->references('id')->on('kingdoms')->onDelete('restrict');
            $table->unsignedBigInteger('status_id')->nullable()->comment('Status da expedição.');
            $table->unsignedBigInteger('user_id')->nullable()->comment('Usuário do Conselho que alterou o status da expedição');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');

            $table->timestamp('start_date')->nullable()->comment('Data de início da expedição');
            //$table->timestamp('end_date')->nullable();  

            $table->text('artifacts')->nullable();
            $table->text('note')->nullable();
            $table->string('rejection_reason')->nullable()->comment('Motivo de rejeição da expedição, informada pelo Conselho.');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expeditions');
    }
};
