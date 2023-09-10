<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('v_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('status')->nullable();
            $table->string('forn_type')->nullable();
            $table->unsignedBigInteger('Forn_id')->nullable();
            $table->foreignId('pak_id')->nullable()->references('id')->on('p_v_tickets')->onDelete('cascade');
            $table->foreignId('bus_id')->nullable()->references('id')->on('buses')->onDelete('cascade');
            $table->foreignId('ligne_id')->nullable()->references('id')->on('lignes')->onDelete('cascade');
            $table->foreignId('arret_from_id')->nullable()->references('id')->on('arrets')->onDelete('cascade');
            $table->foreignId('arret_to_id')->nullable()->references('id')->on('arrets')->onDelete('cascade');
            $table->string('section')->nullable();
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('long', 10, 7)->nullable();
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
        Schema::dropIfExists('v_tickets');
    }
}
