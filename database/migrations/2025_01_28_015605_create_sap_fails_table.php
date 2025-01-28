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
        Schema::create('sap_fails', function (Blueprint $table) {
            $table->uuid('_id')->primary();
            $table->uuid('idCb');
            $table->uuid('idAppr');
            $table->integer('Code');
            $table->string("Desc_fails");

            $table->foreign('idCb')->references('_id')->on('mstr_consumables')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('idAppr')->references('_id')->on('mstr_apprs')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sap_fails');
    }
};