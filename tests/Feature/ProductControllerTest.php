<?php

namespace Tests\Feature;

use App\Models\Offer;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected Offer $offer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->offer = Offer::factory()->create();
        Storage::fake('public');
    }

    public function test_create_product_valid_data_and_image(): void
    {
        $image = UploadedFile::fake()->image('product.jpg');

        $response = $this->post(route('offers.products.store', $this->offer), [
            'name' => 'Super Product',
            'sku' => 'PROD-001',
            'price' => 19.99,
            'state' => 'published',
            'image' => $image
        ]);

        $response->assertRedirect(route('offers.products.index', $this->offer));
    }

    public function test_fail_if_sku_not_unique()
    {
        Product::factory()->create(['sku' => 'DUPLICATE', 'offer_id' => $this->offer->id]);

        $response = $this->post(route('offers.products.store', $this->offer), [
            'name' => 'Autre nom',
            'sku' => 'DUPLICATE',
            'price' => 10,
            'state' => 'published',
        ]);

        $response->assertSessionHasErrors('sku');
    }

    public function test_validate_image_security_and_size()
    {
        $fakePdf = UploadedFile::fake()->create('malicious.pdf', 500);

        $response = $this->post(route('offers.products.store', $this->offer), [
            'image' => $fakePdf,
        ]);

        $response->assertSessionHasErrors('image');
    }

    public function test_allows_updating_without_changing_image_or_sku()
    {
        $originalSku = 'ORIGINAL-SKU';
        $originalImage = 'products/old-image.jpg';

        $product = Product::factory()->create([
            'offer_id' => $this->offer->id,
            'sku'      => $originalSku,
            'image'    => $originalImage,
            'name'     => 'Old name'
        ]);
        $response = $this->patch(route('offers.products.update', [$this->offer, $product]), [
            'name'  => 'New name',
            'sku'   => $originalSku, 
            'price' => 25,
            'state' => 'published',
        ]);

        $response->assertRedirect();
        $product->refresh();

        $this->assertEquals('New name', $product->name);
        $this->assertEquals($originalImage, $product->image);
        $this->assertEquals($originalSku, $product->sku);
    }

    public function test_delete_product()
    {
        $product = Product::factory()->create(['offer_id' => $this->offer->id]);

        $response = $this->delete(route('offers.products.destroy', [$this->offer, $product]));

        $response->assertRedirect();
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_delete_image_file_when_product_deleted()
    {
        Storage::fake('public');
        // Fake image path
        $imagePath = 'products/to-delete.jpg';

        // Create the file
        Storage::disk('public')->put($imagePath, 'dummy content');
        $this->assertTrue(Storage::disk('public')->exists($imagePath));

        $product = Product::factory()->create([
            'offer_id' => $this->offer->id,
            'image' => $imagePath
        ]);
        
        $response = $this->delete(route('offers.products.destroy', [$this->offer, $product]));
        
        $response->assertRedirect();
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
        
        // Check the file is removed
        $this->assertFalse(
            Storage::disk('public')->exists($imagePath), 
            "The file hasn't been deleted from the disk"
        );
    }
}
