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
        Schema::create('widgets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('api_source'); // disease, crypto, worldbank
            $table->string('endpoint')->nullable();
            $table->text('description')->nullable();
            $table->json('default_settings')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Create a table for user widget settings/customizations
        Schema::create('user_widgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('widget_id')->constrained()->onDelete('cascade');
            $table->json('settings')->nullable();
            $table->integer('position')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_widgets');
        Schema::dropIfExists('widgets');
    }
};
