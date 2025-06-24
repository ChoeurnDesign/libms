<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'author',
        'isbn',
        'category_id',
        'description',
        'cover_image',
        'quantity',
        'available_quantity',
        'location',
        'borrow_count',
    ];

    protected $appends = ['cover_url', 'status', 'is_available'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    public function getCoverUrlAttribute()
    {
        if (!$this->cover_image) {
            return null;
        }

        $paths = [
            'storage/' . $this->cover_image,
            $this->cover_image,
            'storage/book_covers/' . basename($this->cover_image),
            'book_covers/' . basename($this->cover_image)
        ];

        foreach ($paths as $path) {
            if (file_exists(public_path($path))) {
                return asset($path);
            }
        }

        if (Storage::disk('public')->exists($this->cover_image)) {
            return asset('storage/' . $this->cover_image);
        }

        if (Storage::disk('public')->exists('book_covers/' . basename($this->cover_image))) {
            return asset('storage/book_covers/' . basename($this->cover_image));
        }

        return null;
    }

    public function getStatusAttribute()
    {
        return $this->available_quantity > 0 ? 'available' : 'unavailable';
    }

    public function getIsAvailableAttribute()
    {
        return $this->available_quantity > 0;
    }

    public function scopeAvailable($query)
    {
        return $query->where('available_quantity', '>', 0);
    }

    public function scopeByCategory($query, $categoryId)
    {
        if ($categoryId === 'all' || $categoryId === null || $categoryId === '') {
            return $query;
        }
        return $query->where('category_id', $categoryId);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('author', 'like', "%{$search}%")
              ->orWhere('isbn', 'like', "%{$search}%");
        });
    }
}
