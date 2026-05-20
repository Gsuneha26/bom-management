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
        Schema::create('material_allocations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bom_line_item_id');
            $table->string('item_code')->nullable();
            $table->integer('allocated_qty')->default(0);
            $table->string('allocated_to')->nullable();
            $table->string('allocated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_allocations');
    }
};
