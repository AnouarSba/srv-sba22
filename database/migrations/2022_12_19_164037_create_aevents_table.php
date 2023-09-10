<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAeventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aevents', function (Blueprint $table) {
            $table->id();
            $table->morphs('user');
            $table->text('cat')->nullable();
            $table->text('type')->nullable();
            $table->text('class_type')->nullable();
            $table->bigInteger('class_id')->nullable();
            $table->text('info')->nullable();
            $table->decimal('amount', 20, 2)->default('0.00');
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
        Schema::dropIfExists('aevents');
    }
}
