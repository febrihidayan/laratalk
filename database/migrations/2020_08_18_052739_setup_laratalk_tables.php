<?php

use FebriHidayan\Laratalk\Config;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config as FacadesConfig;
use Illuminate\Support\Facades\Schema;

class SetupLaratalkTables extends Migration
{
    /**
     * Foreign key type name
     * 
     * @var string
     */
    private $foreignType;

    /**
     *  Foreign key length
     * 
     * @var string
     */
    private $foreignLength;
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->foreignType = FacadesConfig::get('laratalk.users.migration.foreign_key.type');
        $this->foreignLength = FacadesConfig::get('laratalk.users.migration.foreign_key.length');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Config::groups(), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 30);
            $table->string('description', 500)->nullable();
            $table->string('avatar')->nullable();
            $table->{$this->foreignType}('user_id', $this->foreignLength);
            $table->unsignedBigInteger('pinned_message_id')->nullable();
            $table->timestamps();
        });

        Schema::create(Config::groupUser(), function (Blueprint $table) {
            $table->unsignedBigInteger('group_id');
            $table->{$this->foreignType}('user_id', $this->foreignLength);
            $table->boolean('role')->default(0);
        });

        Schema::create(Config::messages(), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->{$this->foreignType}('by_id', $this->foreignLength);
            $table->unsignedBigInteger('group_id')->nullable();
            $table->text('content', 5000)->nullable();
            $table->boolean('type', 2)->default(0);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('delete_user_id')->nullable();
            $table->timestamps();
        });

        Schema::create(Config::messageRecipient(), function (Blueprint $table) {
            $table->unsignedBigInteger('message_id');
            $table->{$this->foreignType}('to_id', $this->foreignLength);
            $table->timestamp('accept_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->boolean('pinned_by')->nullable();
            $table->boolean('pinned_to')->nullable();
        });

        Schema::table(Config::users(), function (Blueprint $table) {
            
            if (!Schema::hasColumn(Config::users(), Config::userAvatar())) {
                $table->string(Config::userAvatar())->nullable();
            }
            
            if (!Schema::hasColumn(Config::users(), Config::userBio())) {
                $table->tinyText(Config::userBio())->nullable();
            }
            
            if (!Schema::hasColumn(Config::users(), Config::userDarkMode())) {
                $table->boolean(Config::userDarkMode())->nullable();
            }
            
            if (!Schema::hasColumn(Config::users(), Config::userLocale())) {
                $table->string(Config::userLocale())->nullable();
            }
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(Config::groups());
        Schema::dropIfExists(Config::groupUser());
        Schema::dropIfExists(Config::messages());
        Schema::dropIfExists(Config::messageRecipient());

        Schema::table(Config::users(), function (Blueprint $table) {
            $table->drop([
                Config::userAvatar(),
                Config::userBio(),
                Config::userDarkMode(),
                Config::userLocale()
            ]);
        });
    }
}
