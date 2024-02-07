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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->unsignedBigInteger('type');
            $table->boolean('status');
            $table->string('um');
            $table->integer('inventory');
            $table->string('cl_1');
            $table->string('cl_2');
            $table->string('cl_3');
            $table->integer('price_1');
            $table->integer('price_2');
            $table->integer('price_3');
            $table->integer('price_4');
            $table->integer('price_5');
            $table->integer('price_6');
            $table->integer('price_7');
            $table->integer('price_8');
            $table->integer('price_9');
            $table->integer('price_10');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
