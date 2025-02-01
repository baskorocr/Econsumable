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
        Schema::create('sap_status', function (Blueprint $table) {
            $table->uuid('_id')->primary();
            $table->uuid('idAppr');
            $table->string("Desc_message");


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