<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Название');
            $table->string('type')->comment('Тип');
            $table->string('name_with_type')->comment('Тип и название одной строкой');
            $table->string('federal_district')->comment('Федеральный округ');
            $table->string('kladr_id')->comment('КЛАДР-код');
            $table->uuid('fias_id')->comment('ФИАС-код');
            $table->string('okato')->comment('Код ОКАТО');
            $table->string('oktmo')->nullable()->comment('Код ОКТМО');
            $table->string('tax_office')->comment('Код ИФНС');
            $table->string('postal_code')->nullable()->comment('Почтовый индекс');
            $table->string('iso_code')->comment('ISO-код');
            $table->string('timezone')->comment('Часовой пояс');
            $table->string('geoname_code')->comment('Код региона по справочнику GeoNames');
            $table->integer('geoname_id')->comment('Идентификатор региона по справочнику GeoNames');
            $table->string('geoname_name')->comment('Англоязычное название региона по справочнику GeoNames');
            $table->unsignedBigInteger('area_id')->comment('ID региона')->nullable();
            $table->foreign('area_id')->references('id')->on('areas');
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
        Schema::dropIfExists('regions');
    }
}
