<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoldCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sold_carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ps_id')->nullable()->references('id')->on('ps_carts')->onDelete('cascade');
            $table->string('skey')->nullable();
            $table->string('client_type')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->string('type')->default('default');
            $table->decimal('sold', 10, 2)->default('0.00');
            $table->string('token')->nullable();
            $table->boolean('valid')->default(1);
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
        Schema::dropIfExists('sold_carts');
    }
}
