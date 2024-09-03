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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained();
            $table->foreignId('status_id')->constrained()->default('1');
            $table->string('batch_number')->unique();
            $table->text('serial_number')->nullable();
            $table->integer('qty')->default('0');
            $table->integer('price');
            $table->date('production_date');
            $table->date('expiration_date')->nullable();
            $table->string('warranty_period')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
