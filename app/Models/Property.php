<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $guarded = [];
    protected $casts = ['is_active' => 'boolean'];

    // Human labels for the type enum (used in URLs and headings)
    public const TYPES = [
        'land'      => 'Land for Sale',
        'new_house' => 'New Houses for Sale',
        'resale'    => 'Resale Houses',
    ];

    // URLs use the slug instead of the numeric id
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Build a unique slug "title-location" once, when the record is created
    protected static function booted(): void
    {
        static::creating(function (Property $p) {
            $base = \Illuminate\Support\Str::slug($p->title . ' ' . $p->location) ?: 'property';
            $slug = $base;
            for ($i = 2; static::where('slug', $slug)->exists(); $i++) {
                $slug = "$base-$i";
            }
            $p->slug = $slug;
        });
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true)->orderBy('sort')->latest('id');
    }

    // Indian-style price: ₹18,00,000
    public function getPriceFormattedAttribute(): string
    {
        $n = (string) $this->price;
        $last3 = substr($n, -3);
        $rest = substr($n, 0, -3);
        if ($rest !== '') {
            $rest = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest);
            $last3 = $rest . ',' . $last3;
        }
        return '₹' . $last3;
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image ? asset('storage/' . $this->image) : asset('img/placeholder.svg');
    }
}
