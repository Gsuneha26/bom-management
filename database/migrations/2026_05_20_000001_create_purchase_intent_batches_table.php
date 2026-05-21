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
             $table->engine = 'InnoDB';

            $table->id();

            $table->foreignId('bom_header_id')
                ->constrained('purchase_intent_batches')
                ->cascadeOnDelete();

            $table->string('batch_reference');

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
