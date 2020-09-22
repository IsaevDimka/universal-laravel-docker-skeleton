<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id');
            $table->string('username')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->unique()->nullable();
            $table->boolean('phone_is_verify')->default(false);
            $table->string('telegram_chat_id')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_visit_at')->nullable();
            $table->string('password');
            $table->boolean('is_active')->default(false);
            $table->char('locale', 2)->default(config('app.fallback_locale'));
            $table->json('options')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
