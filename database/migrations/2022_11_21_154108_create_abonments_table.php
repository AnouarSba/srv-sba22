<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbonmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abonments', function (Blueprint $table) {
            $table->id();
            $table->morphs('abon');
            $table->text('type')->nullable();
            $table->decimal('amount', 20, 2)->default('0.00');
            $table->dateTime('start')->nullable();
            $table->dateTime('end')->nullable();
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
        Schema::dropIfExists('abonments');
    }
}
