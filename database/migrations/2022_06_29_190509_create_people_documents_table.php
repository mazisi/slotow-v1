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
        Schema::create('people_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('people_id')->constrained()->onDelete('cascade');
            $table->string('document_name')->nullable();
            $table->string('document')->nullable();
            $table->string('expiry_date')->nullable();
            $table->string('doc_type')->nullable();
            $table->string('path')->nullable();
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
        Schema::dropIfExists('people_documents');
    }
};
