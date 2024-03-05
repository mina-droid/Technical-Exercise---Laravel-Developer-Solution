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
    { {
            Schema::table('products', function (Blueprint $table) {
                $table->foreign('default_variant_id')->references('id')->on('variants')->onDelete('set null');
            });

            Schema::table('variants', function (Blueprint $table) {
                $table->foreign('product_id')->references('id')->on('products');
            });

            Schema::table('option_product', function (Blueprint $table) {
                $table->foreign('option_id')->references('id')->on('options')->onDelete('cascade');
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            });

            Schema::table('option_variant', function (Blueprint $table) {
                $table->foreign('option_id')->references('id')->on('options')->onDelete('cascade');
                $table->foreign('variant_id')->references('id')->on('variants')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('products_default_variant_id_foreign');
        });

        Schema::table('variants', function (Blueprint $table) {
            $table->dropForeign('variants_product_id_foreign');
        });
    }
};
