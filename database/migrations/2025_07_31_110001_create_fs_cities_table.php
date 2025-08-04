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
        if (!Schema::hasTable('cities')) {
            Schema::create('cities', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code')->nullable();
                $table->string('alias')->nullable();
                $table->unsignedBigInteger('country_id');
                $table->text('description')->nullable();
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
                
                $table->foreign('country_id')->references('id')->on('fs_country')->onDelete('cascade');
                $table->index(['status', 'country_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
