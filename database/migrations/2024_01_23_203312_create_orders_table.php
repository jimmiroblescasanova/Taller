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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('contact_id')->constrained();
            $table->foreignId('vehicle_id')->nullable()->constrained();
            $table->longText('notes')->nullable();
            $table->string('status')->default('pending');
            $table->foreignId('agent_id')->constrained();
            $table->foreignId('specialist_id')->nullable()->constrained();
            $table->foreignId('station_id')->nullable()->constrained();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('ended_at')->nullable();
            $table->integer('subtotal')->default(0);
            $table->integer('tax')->default(0);
            $table->integer('total')->default(0);
            $table->foreignId('estimate_id')->nullable()->constrained()->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
