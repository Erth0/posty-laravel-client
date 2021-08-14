<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostyArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posty_articles', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('title');
            $table->text('summary')->nullable();
            $table->text('body')->nullable();
            $table->dateTime('published_at')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('featured_image_caption')->nullable();
            $table->json('meta')->nullable();
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
        Schema::dropIfExists('posty_articles');
    }
}
