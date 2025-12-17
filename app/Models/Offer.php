<?php

namespace App\Models;

use Database\Factories\OfferFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string $image
 * @property string $state
 */
class Offer extends Model
{
    /** @use HasFactory<OfferFactory> */
    use HasFactory;

    /** @var array<string, string> */
    public static $states = [
        'draft' => 'Brouillon',
        'published' => 'Publié',
        'hidden' => 'Masqué',
    ];

    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
        'state',
    ];

    /**
     * @param  Builder<Offer>  $query
     * @param  string  $state
     * @return Builder<Offer>
     */
    public function scopeOfState($query, $state)
    {
        return $query->where('state', $state);
    }

    /**
     * @return HasMany<Product, $this>
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
