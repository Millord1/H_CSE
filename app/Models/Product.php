<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
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

    /**
     * @return BelongsTo<Offer, $this>
     */
    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }
}
