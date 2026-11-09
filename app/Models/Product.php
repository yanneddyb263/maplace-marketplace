<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'name',
        'description',
        'category',
        'price',
        'image_url',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:0',
            'is_active' => 'boolean',
        ];
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function scopeFilterName(Builder $query, ?string $name): Builder
    {
        return $query->when($name !== null && $name !== '', fn (Builder $query) => $query->where('name', 'like', '%'.$name.'%'));
    }

    public function scopeFilterCategory(Builder $query, ?string $category): Builder
    {
        return $query->when($category !== null && $category !== '', fn (Builder $query) => $query->where('category', 'like', '%'.$category.'%'));
    }

    public function scopeFilterSeller(Builder $query, ?string $seller): Builder
    {
        return $query->when($seller !== null && $seller !== '', fn (Builder $query) => $query->whereHas(
            'seller',
            fn (Builder $query) => $query->where('name', 'like', '%'.$seller.'%')
                ->orWhere('email', 'like', '%'.$seller.'%'),
        ));
    }
}
