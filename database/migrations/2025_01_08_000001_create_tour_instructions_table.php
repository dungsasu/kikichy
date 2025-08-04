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
        Schema::create('tour_instructions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('alias')->nullable();
            $table->integer('ordering')->default(0);
            $table->tinyInteger('published')->default(1);
            $table->string('meta_title')->nullable();
            $table->text('meta_keyword')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('mo_ta_ngan')->nullable();
            $table->longText('mo_ta_chi_tiet')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
            
            $table->index(['published', 'ordering']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_instructions');
    }
};
