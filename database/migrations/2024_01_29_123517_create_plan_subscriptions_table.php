<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('plan_name');
            $table->integer('months');
            $table->boolean('access_to_video')->default(0);
            $table->boolean('access_to_notes')->default(0);
            $table->boolean('access_to_question_bank')->default(0);
            $table->decimal('amount', 10, 2);
            $table->decimal('discount', 5, 2)->default(0);
            $table->integer('watch_hours')->default(0);
            $table->decimal('payable_amount', 10, 2);
            $table->string('plan_type');
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
        Schema::dropIfExists('plan_subscriptions');
    }
}
