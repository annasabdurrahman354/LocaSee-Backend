<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->longText('desc');
            $table->bigInteger('price');
            $table->double('area');
            $table->string('address');
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->string('rating_restaurant')->nullable();
            $table->json('images');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('provinsi_id')->nullable();
            $table->foreign('provinsi_id')->references('id')->on('provinsis');
            $table->unsignedBigInteger('kabupaten_id')->nullable();
            $table->foreign('kabupaten_id')->references('id')->on('kabupatens');
            $table->unsignedBigInteger('kecamatan_id')->nullable();
            $table->foreign('kecamatan_id')->references('id')->on('kecamatans');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
