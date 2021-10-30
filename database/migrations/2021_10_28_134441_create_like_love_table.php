<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikeLoveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('like_love', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('likeable');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type_id', [
                'like',
                'dislike',
                'love',
            ])->default('like');
            $table->timestamp('created_at');

            $table->unique([
                'likeable_id',
                'likeable_type',
                'user_id',
            ], 'like_user_unique');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('like_love');
    }
}
