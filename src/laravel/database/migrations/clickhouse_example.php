<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \App\Models\Clickhouse;

class CreateCikCommissionsTable extends Migration
{
    private string $table_name = 'table_name';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create($this->table_name, function (Blueprint $table) {
//            $table->id();
//            $table->uuid('uuid');
//            $table->string('url');
//            $table->string('area_code')->nullable();
//            $table->unsignedBigInteger('uik_number');
//            $table->json('raw')->nullable();
//            $table->boolean('is_found');
//            $table->timestamps();
//        });

        /**
         * For clickhouse
         */
        $engine = 'MergeTree(date, (area_code, uik_number), 8192)';
        $columns = [
            'uuid'           => Clickhouse::COLUMN_UUID,
            'url'            => Clickhouse::COLUMN_STRING,
            'area_code'      => Clickhouse::COLUMN_UINT8,
            'uik_number'     => Clickhouse::COLUMN_STRING,
            'raw'            => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'voting_vrn'     => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'voting_name'    => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'voting_address' => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'voting_descr'   => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'voting_phone'   => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'voting_lat'     => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'voting_lon'     => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'is_found'       => Clickhouse::COLUMN_UINT8,
            'time'           => Clickhouse::COLUMN_DATETIME,
            'date'           => 'DEFAULT toDate(time)',
        ];
        Clickhouse::createTableIfNotExists($this->table_name, $engine, $columns);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::dropIfExists($this->table_name);
        Clickhouse::dropTableIfExists($this->table_name);
    }
}
