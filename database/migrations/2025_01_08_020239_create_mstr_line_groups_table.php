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
            $table->uuid('_id')->primary();
            $table->string('Lg_code');
            $table->uuid('Lg_plId');
            $table->uuid('Lg_csId');
            $table->uuid('Lg_lineId');
            $table->uuid('Lg_groupId');
            $table->uuid('Lg_slocId');


            $table->string('NpkLeader');
            $table->string('NpkSection');
            $table->string('NpkPjStock');


            $table->foreign('NpkLeader')->references('npk')->on('users');
            $table->foreign('NpkSection')->references('npk')->on('users');

            $table->foreign('NpkPjStock')->references('npk')->on('users');
            $table->foreign('Lg_plId')->references('_id')->on('mstr_plans')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('Lg_csId')->references('_id')->on('mstr_cost_centers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('Lg_lineId')->references('_id')->on('mstr_lines')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('Lg_groupId')->references('_id')->on('mstr_groups')->onUpdate('cascade')->onDelete('cascade');


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