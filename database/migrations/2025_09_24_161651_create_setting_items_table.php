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
        Schema::create('setting_items', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('setting_id')->nullable()->constrained('settings')->nullOnDelete();
            $table->string('name');
            $table->string('key');
            $table->string('type');
            $table->text('options')->nullable();  // example: [1 => 'Option 1', 2 => 'Option 2']
            $table->string('options_source')->nullable(); // example: App\Models\User
            $table->text('value')->nullable();
            $table->text('helper_text')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_items');
    }
};
