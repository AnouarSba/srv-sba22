<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frods', function (Blueprint $table) {
            $table->id();
            $table->string('client_type')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreignId('bus_id')->nullable()->references('id')->on('buses');
            $table->foreignId('ligne_id')->nullable()->references('id')->on('lignes');
            $table->decimal('lat', 10, 7);
            $table->decimal('long', 10, 7);
            $table->decimal('amount', 20, 2)->default('0.00');
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
        Schema::dropIfExists('frods');
    }
}
