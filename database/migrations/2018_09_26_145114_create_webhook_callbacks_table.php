<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebhookCallbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webhook_call_backs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('webhook_id')->references('id')->on('webhooks')->onDelete('cascade');
            $table->string('message');
            $table->integer('scheduled_waiting_time')->default(0);
            $table->string('status')->default('failed');
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
        Schema::dropIfExists('webhookCallbacks');
    }
}
