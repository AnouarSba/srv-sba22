<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartVsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_vs', function (Blueprint $table) {
            $table->id();
            $table->string('valid')->default('no');
            $table->string('type')->default('sahl');
            $table->string('skey');
            $table->decimal('amount', 20, 2)->default('0.00');
            $table->decimal('last', 20, 2)->default('0.00');
            $table->string('carttok')->nullable();
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
        Schema::dropIfExists('cart_vs');
    }
}
