<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('tag_id')->nullable();
            $table->unsignedBigInteger('image_id')->nullable();
            $table->timestamps();

            // Foreign key relationships
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('set null');
            $table->foreign('image_id')->references('id')->on('images')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('blogs');
    }
}
