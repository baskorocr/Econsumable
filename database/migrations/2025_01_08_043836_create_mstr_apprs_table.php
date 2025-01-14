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
            $table->string('Order_no')->primary();
            $table->string('ConsumableId');
            $table->string('CreateNpk');
            $table->date('ApprSectDate');
            $table->date('ApprDeptDate');
            $table->date('ApprPjStokDate');
            $table->integer('status')->default(1);
            $table->string('token')->nullable();

            $table->foreign('CreateNpk')->references('npk')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('consumableId')->references('Cb_number')->on('mstr_consumables')->onUpdate('cascade')->onDelete('cascade');

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