<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pc_id')->nullable()->references('id')->on('p__carts')->onDelete('cascade');
            $table->string('type');
            $table->string('token')->unique();
            $table->string('rfid')->nullable();
            $table->string('tag')->nullable();
            $table->string('pin')->nullable();
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
        Schema::dropIfExists('carts');
    }
}
