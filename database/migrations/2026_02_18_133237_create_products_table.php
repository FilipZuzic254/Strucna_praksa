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
            $table->string('name', 200);
            $table->string('sku', 10)->unique()->nullable();
            $table->foreignId('kpd_code_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->enum('unit_of_measure', ['piece', 'kg', 'l', 'month', 'day', 'hour'])->default('piece');
            $table->integer('discount')->nullable()->default(0);
            $table->enum('tax_rate', ['0', '5', '13', '25'])->default('25');
            $table->foreignId('tax_exemption_id')->nullable()->constrained()->onDelete('set null');
            $table->string('description', 1000)->nullable();
            $table->integer('warranty_months')->default(24);
            $table->foreignId('gallery_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('header_image_id')->nullable()->constrained('gallery_images')->onDelete('set null');
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
