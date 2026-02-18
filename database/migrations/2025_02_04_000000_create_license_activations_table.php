<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('license_activations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('license_id')->nullable()->constrained()->nullOnDelete();
            $table->string('domain');
            $table->string('ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->boolean('success')->default(true);
            $table->string('failure_reason')->nullable();
            $table->timestamp('validated_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('license_activations');
    }
};
