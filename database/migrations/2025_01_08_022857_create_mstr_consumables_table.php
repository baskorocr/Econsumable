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
            $table->string('Cb_number')->primary();
            $table->string('Cb_mtId');

            $table->string('Cb_desc');
            $table->timestamps();

            $table->foreign('Cb_mtId')->references('Mt_number')->on('mstr_materials')->onDelete('cascade')->onUpdate('cascade');


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