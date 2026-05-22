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
        Schema::create('purchase_intents', function (Blueprint $table) {
           $table->id();

           $table->foreignId('batch_id')
                ->constrained('purchase_intent_batches')
                ->cascadeOnDelete()
                ->nullable();

            $table->foreignId('bom_line_item_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('item_code')->nullable();

            $table->text('description')->nullable();

            $table->decimal('required_qty', 12, 2)->default(0);

            $table->decimal('available_qty', 12, 2)->default(0);

            $table->decimal('shortfall_qty', 12, 2)->default(0);

            $table->string('priority')->default('Medium');

            $table->string('status')
                ->default('Pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_intents');
    }
};
