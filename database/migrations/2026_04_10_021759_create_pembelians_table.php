<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembelians', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->date('tanggal');
            $table->integer('price');
            $table->integer('bayar');
            $table->integer('kembalian');
            $table->enum('status_member', ['non_member', 'member']);
            $table->string('no_hp')->nullable();
            $table->integer('poin')->default(0);
             $table->longText('detail_produk')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembelians');
    }
};
