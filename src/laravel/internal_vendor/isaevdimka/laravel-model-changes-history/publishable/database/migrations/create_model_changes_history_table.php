<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use ModelChangesHistory\Models\Change;

class CreateModelChangesHistoryTable extends Migration
{
    protected $tableName;

    public function __construct()
    {
        $this->connection = config('model_changes_history.stores.database.connection', null);
        $this->tableName = config('model_changes_history.stores.database.table', 'model_changes_history');
    }

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('model_id');
            $table->string('model_type');

            $table->jsonb('before_changes')->nullable();
            $table->jsonb('after_changes')->nullable();

            $table->jsonb('changes')->nullable();

            $table->enum('change_type', [
                Change::TYPE_CREATED,
                Change::TYPE_UPDATED,
                Change::TYPE_DELETED,
                Change::TYPE_RESTORED,
                Change::TYPE_FORCE_DELETED,
            ]);

            $table->string('changer_type')->nullable();
            $table->unsignedBigInteger('changer_id')->nullable();

            $table->jsonb('stack_trace')->nullable();

            $table->timestamp(Change::CREATED_AT);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop($this->tableName);
    }
}
