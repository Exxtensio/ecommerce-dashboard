<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('name_local');
            $table->string('code', 6);
            $table->timestamps();
        });

        Schema::create('translations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('model_type');
            $table->ulid('model_id');
            $table->string('locale', 6);
            $table->string('column');
            $table->text('value')->nullable();
            $table->timestamps();

            $table->index(['model_id', 'model_type'], 'translations_model_id_model_type_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
        Schema::dropIfExists('translations');
    }
};
