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
        Schema::create('mstr_materials', function (Blueprint $table) {
            $table->uuid('_id')->primary();
            $table->string('Mt_number');
            $table->uuid('Mt_lgId');

            $table->string('Mt_desc');
            $table->timestamps();

            $table->foreign('Mt_lgId')->references('_id')->on('mstr_line_groups')->onDelete('cascade')->onUpdate('cascade');
            // $table->foreign('Mt_typeId')->references('id')->on('mstr_type_materials')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mstr_materials');
    }
};