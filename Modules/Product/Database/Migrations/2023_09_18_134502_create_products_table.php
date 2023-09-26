<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('brand_id')->index();
            $table->string('name');
            $table->string('sku')->unique()->comment('Code Product');
            ;
            $table->longText('description')->nullable();
            $table->text('warranty_information')->nullable();
            $table->integer('quantity')->default(0);
            $table->float('price', 12, 2)->nullable();
            $table->text('images')->nullable();
            $table->timestamps();
            $table->softDeletes()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
