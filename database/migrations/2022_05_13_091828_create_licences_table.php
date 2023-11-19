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
        Schema::create('licences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('people_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('licence_type_id')->constrained()->onDelete('cascade');
            $table->string('trading_name');
            $table->string('licence_number')->nullable();
            $table->string('client_number')->nullable();
            $table->string('old_licence_number')->nullable();
            $table->date('licence_date')->nullable();
            $table->string('address')->nullable();
            $table->string('address2')->nullable();
            $table->string('address3')->nullable();
            $table->string('province')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('belongs_to')->nullable();
            $table->string('reg_number')->nullable();
            $table->string('id_number')->nullable();
            $table->string('board_region')->nullable();
            $table->string('is_new_app')->nullable();
            $table->string('status')->nullable();
            $table->date('deposit_paid_at')->nullable();
            $table->date('liquor_board_at')->nullable();
            $table->date('application_lodged_at')->nullable();
            $table->date('initial_inspection_at')->nullable(); 
            $table->date('final_inspection_at')->nullable();
            $table->date('activation_fee_requested_at')->nullable();
            $table->date('client_paid_at')->nullable();
            $table->date('activation_fee_paid_at')->nullable();
            $table->date('licence_issued_at')->nullable(); 
            $table->date('licence_delivered_at')->nullable(); 
            $table->boolean('is_licence_active')->nullable();
            $table->string('latest_renewal')->nullable();
            $table->string('renewal_amount')->nullable();
            $table->string('merged_document')->nullable();
            $table->string('client_quoted_invoice_number')->nullable();
            $table->string('is_client_invoiced')->nullable();
            $table->string('is_finalisation_doc_uploaded')->nullable();
            $table->string('is_application_logded_doc_uploaded')->nullable();
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
        Schema::table('licences', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
    
};
