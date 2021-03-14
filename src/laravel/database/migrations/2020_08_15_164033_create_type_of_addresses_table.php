<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypeOfAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_of_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('type_id')->comment('Уникальный идентификатор типа');
            $table->unsignedInteger('fias_level')->comment('Уровень адресного объекта');
            $table->string('name')->comment('Краткое название типа');
            $table->string('name_full')->comment('Полное название типа');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('type_of_addresses');
    }
}
