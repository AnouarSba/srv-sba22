<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('p__carts', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('status')->nullable();
            $table->string('token')->nullable();
            $table->BigInteger('start')->nullable();
            $table->BigInteger('end')->nullable();
            $table->string('forn_type')->nullable();
            $table->unsignedBigInteger('Forn_id')->nullable();
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
        Schema::dropIfExists('p__carts');
    }
}
