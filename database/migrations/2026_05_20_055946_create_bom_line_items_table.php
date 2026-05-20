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
        Schema::create('bom_line_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bom_header_id');
            $table->string('item_code');
            $table->string('description');
            $table->integer('required_qty')->default(0);
            $table->string('unit')->nullable();
            $table->text('specifications')->nullable();
            $table->string('allocated_to')->nullable();
            $table->string('inventory_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bom_line_items');
    }
};
