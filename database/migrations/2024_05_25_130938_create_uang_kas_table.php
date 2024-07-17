<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uang_kas', function (Blueprint $table) {
            $table->id();
            $table->string('bulan');
            $table->integer('pemasukan')->nullable()->default(0);
            $table->integer('pengeluaran')->nullable()->default(0);
            $table->integer('saldo')->default(0);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uang_kas');
    }
};
