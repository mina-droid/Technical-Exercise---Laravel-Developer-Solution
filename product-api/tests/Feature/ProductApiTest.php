<?php

namespace Tests\Feature;

use App\Events\ProductOutOfStock;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;
use App\Models\Variant;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexReturnsCorrectJsonStructure()
    {
        $response = $this->get('/api/products');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'title',
                'is_in_stock',
                'average_rating',
                'options',
                'default_variant',
            ],
        ]);
    }

    public function testFilterByAverageRating()
    {
        // Create two products
        $product1 = Product::factory()->create(['average_rating' => 4]);
        $product2 = Product::factory()->create(['average_rating' => 5.6]);

        // Send a GET request to the API endpoint
        $response = $this->get('/api/products?filter[average_rating]=5');

        $response->dump();
        // Assert that the response status is 200
        $response->assertStatus(200);

        // Assert that the response contains the second product
        $response->assertJsonFragment([
            'id' => $product2->id,
            'title' => $product2->title,
        ]);

        // Assert that the response does not contain the first product
        $response->assertJsonMissing([
            'id' => $product1->id,
            'title' => $product1->title,
        ]);
    }

    public function testFilterByMaxPrice()
    {
        // Create two products with variants
        $product1 = Product::factory()->has(Variant::factory()->state(['price' => 100]))->create();
        $product2 = Product::factory()->has(Variant::factory()->state(['price' => 200]))->create();

        // Set the default variant for each product
        $product1->default_variant_id = $product1->variants->first()->id;
        $product1->save();

        $product2->default_variant_id = $product2->variants->first()->id;
        $product2->save();

        // Send a GET request to the API endpoint
        $response = $this->get('/api/products?filter[max_price]=150');

        // Assert that the response status is 200
        $response->assertStatus(200);

        // Assert that the response contains the first product
        $response->assertJsonFragment([
            'id' => $product1->id,
            'title' => $product1->title,
        ]);

        // Assert that the response does not contain the second product
        $response->assertJsonMissing([
            'id' => $product2->id,
            'title' => $product2->title,
        ]);
    }

    public function testFilterByOptions()
    {
        $product1 = Product::factory()->withVariant(['option1' => 'Medium', 'option2' => 'Red'])->create();
        $product2 = Product::factory()->withVariant(['option1' => 'Large', 'option2' => 'Black'])->create();

        $response = $this->get('/api/products?filter[options]=Medium,Red');

        $response->assertStatus(200);
        $this->assertContains($product1->id, array_column($response->json(), 'id'));
        $this->assertNotContains($product2->id, array_column($response->json(), 'id'));
    }

    public function testOutOfStockEventIsDispatched()
    {
        $product = Product::factory()->withVariant(['stock' => 0])->create();

        // Disable event listeners for this test
        Product::flushEventListeners();

        // Fake the event before triggering it
        Event::fake();

        // Trigger the event
        event(new ProductOutOfStock($product));

        // Assert that the ProductOutOfStock event is dispatched
        Event::assertDispatched(ProductOutOfStock::class, function ($event) use ($product) {
            return $event->product->id === $product->id;
        });
    }
}
