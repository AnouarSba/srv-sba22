<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateValideursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('valideurs', function (Blueprint $table) {
            $table->id();
            $table->string('device_id')->nullable();
            $table->string('imei')->nullable();
            $table->string('type')->nullable();
            $table->string('status')->nullable();
            $table->foreignId('bus_id')->nullable()->references('id')->on('buses');
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
        Schema::dropIfExists('valideurs');
    }
}
