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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            // Ai làm (có thể null nếu guest)
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Hành động gì: login, booking_created, movie_created, ...
            $table->string('event')->index();

            // Đối tượng bị tác động (polymorphic)
            $table->nullableMorphs('subject'); 
            // subject_type: App\Models\Movie, subject_id: 5

            // Mô tả ngắn cho dễ đọc
            $table->string('description')->nullable();

            // Data thêm (JSON)
            $table->json('properties')->nullable();

            // Thông tin request
            $table->string('ip', 45)->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
