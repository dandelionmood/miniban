<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Initialization extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('board_user', function (Blueprint $table) {
            $table->foreignId('board_id');
            $table->foreignId('user_id');
            $table->index(['board_id', 'user_id']);
        });

        Schema::create('boards', function (Blueprint $table) {
            $table->id();

            $table->string('slug');
            $table->string('label');
            $table->boolean('is_public')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
        });

        Schema::create('columns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_id');

            $table->string('label');
            $table->unsignedTinyInteger('position');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['board_id', 'position']);
        });

        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('column_id');
            $table->foreignId('board_id');

            $table->string('label');
            $table->unsignedTinyInteger('position');

            $table->timestamps();
            $table->softDeletes();
            
            $table->index('board_id');
            $table->index(['column_id', 'position']);
        });

        Schema::create('widgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id');

            $table->string('type', 16);
            $table->unsignedTinyInteger('position');
            $table->json('data');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['card_id', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('board_user');
        Schema::drop('boards');
        Schema::drop('columns');
        Schema::drop('cards');
        Schema::drop('widgets');
    }
}
