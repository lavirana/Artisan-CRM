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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // call, email, meeting, note
            $table->text('description');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Rep who did the activity
            $table->foreignId('contact_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('deal_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
