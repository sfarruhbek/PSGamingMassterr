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
        Schema::create('device_product_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('history_id')->nullable();
            $table->foreignId('device_id')->nullable();
            $table->foreignId('product_id')->constrained();
            $table->integer('count');
            $table->decimal('sold', 15, 2);
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_product_histories');
    }
};
