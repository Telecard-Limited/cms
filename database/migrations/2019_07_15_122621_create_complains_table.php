<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComplainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complains', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_id')->nullable();
            $table->text('desc')->nullable();
            $table->string('remarks')->nullable();
            $table->string('title')->nullable();
            $table->string('informed_to')->nullable();
            $table->string('informed_by')->nullable();
            $table->dateTime('order_datetime')->nullable();
            $table->dateTime('promised_time')->nullable();

            $table->unsignedBigInteger('outlet_id');
            $table->unsignedBigInteger('ticket_status_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('customer_id');
            $table->softDeletes();
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
        Schema::dropIfExists('complains');
    }
}
