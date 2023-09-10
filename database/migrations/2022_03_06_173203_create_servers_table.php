<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->string('key');
            $table->string('type');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('email2')->nullable();
            $table->string('password2')->nullable();
            $table->string('token')->nullable();
            $table->boolean('enable')->default(true);
            $table->boolean('connect')->default(false);
            $table->softDeletes();
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
        Schema::dropIfExists('servers');
    }
}
