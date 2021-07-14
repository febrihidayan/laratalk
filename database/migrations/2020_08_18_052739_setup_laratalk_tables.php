<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetupLaratalkTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laratalk_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('avatar')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });

        Schema::create('laratalk_group_user', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('role')->default(0);
            $table->timestamps();
        });

        Schema::create('laratalk_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('from_id');
            $table->unsignedBigInteger('group_id')->nullable();
            $table->text('content', 5000)->nullable();
            $table->enum('type', ['message', 'add', 'remove'])->default('message');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('laratalk_message_meta', function (Blueprint $table) {
            $table->unsignedBigInteger('message_id');
            $table->unsignedBigInteger('to_id');
            $table->timestamp('accept_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->boolean('pinned')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laratalk_group_user');
        Schema::dropIfExists('laratalk_groups');
        Schema::dropIfExists('laratalk_messages');
    }
}
