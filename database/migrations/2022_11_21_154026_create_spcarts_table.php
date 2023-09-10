<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpcartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spcarts', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('type')->nullable();
            $table->string('name')->nullable();
            $table->date('date_nes')->nullable();
            $table->string('photo')->nullable();
            $table->string('token')->default('no');
            $table->integer('cont')->default(0);
            $table->integer('max')->nullable();
            $table->boolean('valid')->default(false);
            $table->boolean('ban')->default(false);
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
        Schema::dropIfExists('spcarts');
    }
}
