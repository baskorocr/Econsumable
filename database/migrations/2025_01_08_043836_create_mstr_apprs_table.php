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
        Schema::create('mstr_apprs', function (Blueprint $table) {
            $table->uuid('_id')->primary();
            $table->uuid('no_order');
            $table->uuid('ConsumableId');

            $table->string('NpkSect')->nullable();
            $table->string('NpkDept')->nullable();
            $table->string('NpkPj')->nullable();
            $table->date('ApprSectDate')->nullable();
            $table->date('ApprDeptDate')->nullable();
            $table->date('ApprPjStokDate')->nullable();
            $table->integer('status')->default(1);
            $table->string('token')->nullable();
            $table->integer('jumlah');

            // Menambahkan foreign key constraint
            $table->foreign('no_order')->references('_id')->on('order_segments')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('ConsumableId')->references('_id')->on('mstr_consumables')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('NpkSect')->references('npk')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('NpkDept')->references('npk')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('NpkPj')->references('npk')->on('users')->onUpdate('cascade')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mstr_apprs');
    }
};