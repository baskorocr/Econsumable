<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mstr_consumables', function (Blueprint $table) {
            $table->uuid('_id')->primary();
            $table->string('Cb_number');
            $table->uuid('Cb_lgId');
            $table->string('Cb_type');
            $table->string('Cb_IO');

            $table->foreign('Cb_lgId')->references('_id')->on('mstr_line_groups')->onUpdate('cascade')->onDelete('cascade');

            $table->string('Cb_desc');
            $table->timestamps();




        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mstr_consumables');
    }
};