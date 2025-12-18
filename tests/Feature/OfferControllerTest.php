<?php

namespace Tests\Feature;

use App\Models\Offer;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OfferControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_create_page_is_displayed(): void
    {
        $response = $this->get(route('offers.create'));

        $response->assertOk();
        $response->assertViewIs('offers.create');
    }

    public function test_offer_can_be_stored(): void
    {
        $image = UploadedFile::fake()->image('offer.jpg', 640, 480);

        $response = $this->post(route('offers.store'), [
            'name' => 'New Offer',
            'slug' => 'new-offer',
            'description' => 'Test description',
            'image' => $image,
            'state' => 'draft',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('offers', ['name' => 'New Offer']);

        $offer = Offer::firstOrFail();
        $this->assertTrue(
            Storage::disk('public')->exists($offer->image),
            "The file hasn't been deleted from the disk"
        );
    }

    public function test_edit_page_is_displayed(): void
    {
        $offer = Offer::factory()->create();

        $response = $this->get(route('offers.edit', $offer->id));

        $response->assertOk();
        $response->assertViewHas('offer', $offer);
    }

    public function test_offer_can_be_updated_without_image(): void
    {
        $offer = Offer::factory()->create(['name' => 'Old name']);

        $response = $this->patch(route('offers.update', $offer->id), [
            'name' => 'Modified Name',
            'slug' => 'modified-name',
            'state' => 'published',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('offers', [
            'id' => $offer->id,
            'name' => 'Modified Name',
        ]);
    }

    public function test_offer_can_be_deleted(): void
    {
        $offer = Offer::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('offers.destroy', $offer->id));

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseMissing('offers', ['id' => $offer->id]);
    }

    public function test_offer_show_page_displays_products(): void
    {
        $offer = Offer::factory()
            ->hasProducts(3)
            ->create();

        $response = $this->actingAs($this->user)->get(route('offers.show', $offer->id));

        $response->assertOk();
        $response->assertViewIs('offers.show');
        $response->assertViewHas('offer');

        $this->assertCount(3, $response->viewData('offer')->products);
    }

    public function test_image_deleted_from_disk_when_product_destroyed(): void
    {
        Storage::fake('public');
        $offer = Offer::factory()->create();

        $image = UploadedFile::fake()->image('product-to-delete.jpg');
        $path = $image->store('products', 'public');

        $product = Product::factory()->create([
            'offer_id' => $offer->id,
            'image' => $path,
        ]);

        $this->assertTrue(Storage::disk('public')->exists($path));

        $response = $this->delete(route('offers.products.destroy', [$offer->id, $product->id]));

        $response->assertRedirect();
        $this->assertDatabaseMissing('products', ['id' => $product->id]);

        $this->assertFalse(
            Storage::disk('public')->exists($path),
            "The file hasn't been deleted from the disk"
        );
    }
}
