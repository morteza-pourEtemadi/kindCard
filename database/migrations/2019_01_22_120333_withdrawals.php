<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Withdrawals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index('');
            $table->unsignedInteger('category_id')->index('')->nullable();
            $table->integer('price');
            $table->string('card_number', 19)->nullable();
            $table->string('full_name')->nullable();
            $table->string('mobile', 11)->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->string('tracking_code')->nullable();
            $table->timestamp('payed_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('withdrawals');
    }
}
