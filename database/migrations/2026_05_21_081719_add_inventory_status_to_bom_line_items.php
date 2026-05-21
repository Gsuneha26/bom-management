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
         Schema::table('bom_line_items', function (Blueprint $table) {

            if (!Schema::hasColumn('bom_line_items', 'inventory_status')) {

                $table->string('inventory_status')->nullable();

            }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bom_line_items', function (Blueprint $table) {
            //
        });
    }
};
