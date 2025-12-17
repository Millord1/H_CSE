<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property int $offer_id
 * @property string $name
 * @property string $sku
 * @property string|null $image
 * @property float $price
 * @property string $state
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Offer $offer
 */
class Product extends Model
{
    use HasFactory;

    /** @var array<string,  string> */
    public static $states = [
        'draft' => 'Brouillon',
        'published' => 'Publié',
        'invisible' => 'Invisible',
    ];

    protected $fillable = [
        'offer_id',
        'name',
        'sku',
        'image',
        'price',
        'state',
    ];

    protected static function booted(): void
    {
        static::deleting(function($product){
            if($product->image){
                Storage::disk('public')->delete($product->image);
            }
        });
    }

    /**
     * @return BelongsTo<Offer, $this>
     */
    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }
}
