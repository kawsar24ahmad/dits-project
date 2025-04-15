<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->decimal('offer_price', 10, 2)->nullable();
            $table->unsignedBigInteger('category_id');
            $table->string('thumbnail');
            $table->boolean('is_active')->default(1);
            $table->timestamps();

            // Foreign key constraint (optional, if categories table exists)
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
}
