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
        Schema::create('mstr_line_groups', function (Blueprint $table) {
            $table->string('Lg_code')->primary();
            $table->integer('Lg_plId')->length(4);
            $table->integer('Lg_csId')->length(10);
            $table->string('Lg_lineId');
            $table->unsignedBigInteger('Lg_groupId');
            $table->integer('Lg_slocId')->length(4);


            $table->string('NpkLeader');
            $table->string('NpkSection');
            $table->string('NpkPjStock');


            $table->foreign('NpkLeader')->references('npk')->on('users');
            $table->foreign('NpkSection')->references('npk')->on('users');

            $table->foreign('NpkPjStock')->references('npk')->on('users');
            $table->foreign('Lg_plId')->references('Pl_code')->on('mstr_plans')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('Lg_csId')->references('Cs_code')->on('mstr_cost_centers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('Lg_lineId')->references('id')->on('mstr_lines')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('Lg_groupId')->references('id')->on('mstr_groups')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('Lg_slocId')->references('Tp_mtCode')->on('mstr_slocs')->onUpdate('cascade')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mstr_line_groups');
    }
};