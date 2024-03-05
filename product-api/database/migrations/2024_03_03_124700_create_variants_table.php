<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Schema;

class CreateVariantsTable extends Migration
{
    public function up()
    {
        Schema::create('variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('title');
            $table->string('option1')->default('red');
            $table->string('option2')->default('small');
            $table->double('price');
            $table->integer('stock');
            $table->boolean('is_in_stock')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('variants');
    }
}
