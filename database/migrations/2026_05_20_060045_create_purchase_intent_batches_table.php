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
        Schema::create('purchase_intent_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bom_header_id')->constrained()->cascadeOnDelete();
            $table->string('batch_reference')->unique();
            $table->integer('total_items')->default(0);
            $table->decimal('total_shortfall_qty', 12, 2)->default(0);
            $table->enum('status', [
                'pending',
                'processing',
                'completed'
            ])->default('pending');
            $table->string('created_by')
                ->default('System');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_intent_batches');
    }
};
