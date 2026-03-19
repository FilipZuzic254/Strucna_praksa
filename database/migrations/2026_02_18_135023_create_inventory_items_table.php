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
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->index();
            $table->string('serial_number')->unique();
            $table->enum('status', ['in_stock', 'delivered', 'faulty', 'replaced'])->default('in_stock')->index();
            $table->date('purchased_at')->nullable();
            $table->date('installed_at')->nullable();
            $table->date('warranty_expires_at')->nullable()->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
