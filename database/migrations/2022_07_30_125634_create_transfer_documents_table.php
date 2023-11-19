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
        Schema::create('transfer_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('licence_transfer_id')->constrained()->onDelete('cascade');
            $table->string('document_name')->nullable();
            $table->string('document')->nullable();
            $table->string('doc_type')->nullable();
            $table->string('belongs_to')->nullable();
            $table->integer('num')->nullable();
            $table->string('slug');
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
        Schema::dropIfExists('transfer_documents');
    }
};
