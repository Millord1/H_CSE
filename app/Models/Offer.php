<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Offer extends Model
{
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
     * @param Builder<Offer> $query
     * @param string $state
     * @return Builder<Offer>
     */
    public function scopeOfState( $query, $state)
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
