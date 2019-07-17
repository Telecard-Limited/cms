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
            $table->string('customer_name');
            $table->string('customer_number');
            $table->string('order_number')->nullable();
            $table->text('desc')->nullable();
            $table->text('remarks')->nullable();

            $table->unsignedBigInteger('complainable_id');
            $table->string('complainable_type');

            $table->unsignedBigInteger('ticket_status_id');
            $table->unsignedBigInteger('issue_id');
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
