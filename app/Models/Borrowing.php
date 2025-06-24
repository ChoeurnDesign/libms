<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'borrowed_date',
        'due_date',
        'returned_date',
        'status',
        'fine_amount',
        'notes',
        'renewed_at' // Added 'renewed_at' to fillable
    ];

    protected $casts = [
        'borrowed_date' => 'datetime',
        'due_date' => 'datetime',
        'returned_date' => 'datetime',
        'fine_amount' => 'decimal:2',
        'renewed_at' => 'datetime', // Added 'renewed_at' to casts
    ];

    /**
     * Check if the borrowing is overdue
     */
    public function isOverdue()
    {
        // If already returned, not overdue
        if ($this->returned_date || $this->status === 'returned') {
            return false;
        }

        // If due date is in the past, it's overdue
        return $this->due_date && $this->due_date->isPast();
    }

    /**
     * Get the number of overdue days
     */
    public function getOverdueDays()
    {
        if (!$this->isOverdue()) {
            return 0;
        }

        // Calculate difference in days from due date to now
        return $this->due_date->diffInDays(now());
    }

    /**
     * Calculate fine amount based on overdue days
     */
    public function calculateFine($finePerDay = 1.00)
    {
        if (!$this->isOverdue()) {
            return 0;
        }

        return $this->getOverdueDays() * $finePerDay;
    }

    /**
     * Get the user who borrowed the book
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the borrowed book
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Scope for overdue borrowings
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
            ->whereNull('returned_date')
            ->where('status', '!=', 'returned'); // Ensure it's not already marked as returned
    }

    /**
     * Scope for active borrowings
     */
    public function scopeActive($query)
    {
        return $query->whereNull('returned_date')
            ->where('status', '!=', 'returned');
    }
}
