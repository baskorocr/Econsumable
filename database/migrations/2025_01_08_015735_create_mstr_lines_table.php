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
        Schema::create('mstr_lines', function (Blueprint $table) {
            $table->uuid('_id')->primary();
            $table->uuid('Ln_lgId');
            $table->string('Ln_name');

            $table->foreign('Ln_lgId')->references('_id')->on('mstr_line_groups')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mstr_lines');
    }
};