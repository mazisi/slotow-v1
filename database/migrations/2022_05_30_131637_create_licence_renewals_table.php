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
        Schema::create('licence_renewals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('licence_id')->constrained()->onDelete('cascade');
            $table->string('date');
            $table->string('status')->nullable();
            $table->date('client_paid_at')->nullable();
            $table->date('renewal_issued_at')->nullable();
            $table->date('renewal_delivered_at')->nullable();
            $table->date('payment_to_liquor_board_at')->nullable();
            $table->string('is_quote_sent')->nullable();//updated on email comms
            $table->string('client_invoiced_at')->nullable();
            $table->string('slug');
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
        Schema::table('licence_renewals', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
