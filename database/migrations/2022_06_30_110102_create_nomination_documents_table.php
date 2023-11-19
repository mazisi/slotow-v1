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
        Schema::create('nomination_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nomination_id')->constrained()->onDelete('cascade');
            $table->string('document_name')->nullable();
            $table->date('date')->nullable();
            $table->string('document')->nullable();
            $table->string('doc_type')->nullable();
            $table->string('path')->nullable();
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
        Schema::dropIfExists('nomination_documents');
    }
};
