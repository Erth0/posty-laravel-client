<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostyArticlesTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posty_articles_topics', function (Blueprint $table) {
            $table->bigInteger('article_id')->unsigned()->index();
            $table->bigInteger('topic_id')->unsigned()->index();

            $table->foreign('article_id')->references('id')->on('posty_articles')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('topic_id')->references('id')->on('posty_topics')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posty_articles_topics');
    }
}
