<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cbr_data', function (Blueprint $table) {
            $table->id();
            // возможно добавить значение с атрибутом вылют - id
            $table->string('data_date');
            $table->string('valute_num_code');
            $table->string('valute_char_code');
            $table->string('nominal');
            $table->string('valute_name');
            $table->string('value');
            $table->string('vunit_rate');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cbr_data');
    }
};
