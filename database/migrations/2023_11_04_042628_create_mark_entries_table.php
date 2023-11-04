<?php

use App\Models\Course;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mark_entries', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Course::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->decimal('external', 4, 2, true)
                ->default(0);

            $table->decimal('internal', 4, 2, true)
                ->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mark_entries');
    }
};
