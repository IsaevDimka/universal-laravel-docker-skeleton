<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('address')->comment('Адрес одной строкой');
            $table->string('postal_code')->comment('Почтовый индекс');
            $table->string('country')->comment('Страна');
            $table->string('federal_district')->comment('Федеральный округ');
            $table->unsignedBigInteger('region_id')->comment('ID региона');
            $table->foreign('region_id')->references('id')->on('regions');
            $table->string('region_type')->comment('Тип региона');
            $table->string('region_name')->comment('Регион');
            $table->string('area_type')->nullable()->comment('Тип района');
            $table->string('area')->nullable()->comment('Район');
            $table->string('type')->comment('Тип города');
            $table->string('name')->comment('Город');
            $table->string('name_with_type')->comment('Тип и название одной строкой');
            $table->string('settlement_type')->nullable()->comment('Тип населенного пункта');
            $table->string('settlement')->nullable()->comment('Населенный пункт');
            $table->string('kladr_id')->comment('КЛАДР-код');
            $table->uuid('fias_id')->comment('ФИАС-код');
            $table->smallInteger('fias_level')->comment('Уровень по ФИАС');
            $table->smallInteger('capital_marker')->comment('Признак центра региона или района');
            $table->string('okato')->comment('Код ОКАТО');
            $table->string('oktmo')->comment('Код ОКТМО');
            $table->string('tax_office')->comment('Код ИФНС');
            $table->string('timezone')->comment('Часовой пояс');
            $table->double('geo_lat', 100, 100)->nullable()->comment('Широта');;
            $table->double('geo_lon', 100, 100)->nullable()->comment('Долгота');
            $table->string('population')->comment('Население');
            $table->string('foundation_year')->comment('Год основания');
            $table->boolean('is_active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cities');
    }
}
