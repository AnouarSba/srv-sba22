<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateETicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('e_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('forn_type')->nullable();
            $table->unsignedBigInteger('Forn_id')->nullable();
            $table->string('client_type')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreignId('bus_id')->nullable()->references('id')->on('buses')->onDelete('cascade');
            $table->foreignId('ligne_id')->nullable()->references('id')->on('lignes')->onDelete('cascade');
            $table->foreignId('arret_from_id')->nullable()->references('id')->on('arrets')->onDelete('cascade');
            $table->foreignId('arret_to_id')->nullable()->references('id')->on('arrets')->onDelete('cascade');
            $table->string('section')->nullable();
            $table->decimal('lat', 10, 7);
            $table->decimal('long', 10, 7);
            $table->string('uuid')->nullable();
            $table->string('token')->nullable();
            $table->decimal('amount', 20, 2)->default('0.00');
            $table->boolean('valid')->default(false);
            $table->dateTime('end_valid')->nullable();
            $table->boolean('archived')->default(false);
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
        Schema::dropIfExists('e_tickets');
    }
}
