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
            // database/migrations/xxxx_xx_xx_create_purchase_order_items_table.php
            Schema::create('purchase_order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
                $table->foreignId('sku_id')->constrained('skus')->onDelete('cascade');
                $table->text('description')->nullable();
                $table->integer('quantity');
                $table->decimal('unit_price', 10, 2);
                $table->decimal('total', 12, 2);
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('purchase_order_items');
        }
    };
