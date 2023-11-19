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
        Schema::create('temporal_licences', function (Blueprint $table) {
            $table->id();
            $table->string('belongs_to');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('people_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('event_name')->default(1);
            $table->string('liquor_licence_number')->nullable();
            $table->string('active')->default(1);
            $table->string('status')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('client_paid_at')->nullable();
            $table->string('latest_lodgment_date')->nullable();
            $table->longText('address')->nullable();
            $table->string('application_type')->nullable();
            $table->string('reg_number')->nullable();
            $table->string('id_number')->nullable();
            $table->date('payment_to_liquor_board_at')->nullable();
            $table->date('logded_at')->nullable();
            $table->date('issued_at')->nullable();
            $table->date('delivered_at')->nullable();
            $table->string('merged_document')->nullable();
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
        Schema::table('temporaly_licences', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
