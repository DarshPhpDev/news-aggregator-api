<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title')->index();
            $table->longText('body')->nullable();
            $table->string('category')->nullable()->index();
            $table->string('author')->nullable()->index();
            $table->text('thumb')->nullable();
            $table->text('web_url')->nullable();
            $table->dateTime('published_at')->nullable()->index();
            $table->string('news_source')->nullable();  // newsapi, theguardianapi, newyorktimesapi, ...
            $table->foreignId('source_id')->nullable()->constrained();
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
        Schema::dropIfExists('articles');
    }
};
