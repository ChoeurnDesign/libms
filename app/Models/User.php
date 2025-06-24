<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'student_id',
        'profile_picture',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Role checking methods
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    // Relationship methods
    public function borrowings()
    {
        return $this->hasMany(\App\Models\Borrowing::class);
    }

    public function favorites()
    {
        if (class_exists('\App\Models\Favorite')) {
            return $this->hasMany(\App\Models\Favorite::class);
        }
        return $this->hasMany(\App\Models\User::class)->where('id', 0); // Empty relation
    }

    public function reviews()
    {
        if (class_exists('\App\Models\BookReview')) {
            return $this->hasMany(\App\Models\BookReview::class);
        }
        return $this->hasMany(\App\Models\User::class)->where('id', 0); // Empty relation
    }

    // Borrowing status methods
    public function activeBorrowings()
    {
        if (!class_exists('\App\Models\Borrowing') || !Schema::hasTable('borrowings')) {
            return $this->hasMany(\App\Models\User::class)->where('id', 0); // Empty relation
        }
        return $this->hasMany(\App\Models\Borrowing::class)->where('status', 'borrowed');
    }

    public function overdueBorrowings()
    {
        if (!class_exists('\App\Models\Borrowing') || !Schema::hasTable('borrowings')) {
            return $this->hasMany(\App\Models\User::class)->where('id', 0); // Empty relation
        }
        return $this->hasMany(\App\Models\Borrowing::class)
                   ->where('status', 'borrowed')
                   ->where('due_date', '<', now());
    }

    public function dueSoonBorrowings()
    {
        if (!class_exists('\App\Models\Borrowing') || !Schema::hasTable('borrowings')) {
            return $this->hasMany(\App\Models\User::class)->where('id', 0); // Empty relation
        }
        return $this->hasMany(\App\Models\Borrowing::class)
                   ->where('status', 'borrowed')
                   ->whereBetween('due_date', [now(), now()->addDays(3)]);
    }

    public function returnedBorrowings()
    {
        if (!class_exists('\App\Models\Borrowing') || !Schema::hasTable('borrowings')) {
            return $this->hasMany(\App\Models\User::class)->where('id', 0); // Empty relation
        }
        return $this->hasMany(\App\Models\Borrowing::class)->where('status', 'returned');
    }

    // Boolean check methods
    public function hasOverdueBooks(): bool
    {
        if (!class_exists('\App\Models\Borrowing') || !Schema::hasTable('borrowings')) {
            return false;
        }
        try {
            return $this->overdueBorrowings()->exists();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function hasFavoriteBook($bookId): bool
    {
        if (class_exists('\App\Models\Favorite')) {
            try {
                return $this->favorites()->where('book_id', $bookId)->exists();
            } catch (\Exception $e) {
                return false;
            }
        }
        return false;
    }

    public function hasActiveBorrowingForBook($bookId): bool
    {
        if (!class_exists('\App\Models\Borrowing') || !Schema::hasTable('borrowings')) {
            return false;
        }
        try {
            return $this->activeBorrowings()->where('book_id', $bookId)->exists();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function canBorrowMore(int $limit = 5): bool
    {
        return $this->getCurrentBorrowedCount() < $limit && !$this->hasOverdueBooks();
    }

    // Count methods with safety checks
    public function getCurrentBorrowedCount(): int
    {
        if (!class_exists('\App\Models\Borrowing') || !Schema::hasTable('borrowings')) {
            return 0;
        }
        try {
            return $this->activeBorrowings()->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getOverdueCount(): int
    {
        if (!class_exists('\App\Models\Borrowing') || !Schema::hasTable('borrowings')) {
            return 0;
        }
        try {
            return $this->overdueBorrowings()->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getDueSoonCount(): int
    {
        if (!class_exists('\App\Models\Borrowing') || !Schema::hasTable('borrowings')) {
            return 0;
        }
        try {
            return $this->dueSoonBorrowings()->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getTotalBorrowedCount(): int
    {
        if (!class_exists('\App\Models\Borrowing') || !Schema::hasTable('borrowings')) {
            return 0;
        }
        try {
            return $this->borrowings()->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getTotalReturnedCount(): int
    {
        if (!class_exists('\App\Models\Borrowing') || !Schema::hasTable('borrowings')) {
            return 0;
        }
        try {
            return $this->returnedBorrowings()->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getFavoritesCount(): int
    {
        if (class_exists('\App\Models\Favorite')) {
            try {
                return $this->favorites()->count();
            } catch (\Exception $e) {
                return 0;
            }
        }
        return 0;
    }

    // Fine amount methods
    public function getTotalFineAmount(): float
    {
        if (!class_exists('\App\Models\Borrowing') || !Schema::hasTable('borrowings')) {
            return 0.0;
        }
        try {
            return (float) $this->borrowings()->sum('fine_amount');
        } catch (\Exception $e) {
            return 0.0;
        }
    }

    public function getOutstandingFineAmount(): float
    {
        if (!class_exists('\App\Models\Borrowing') || !Schema::hasTable('borrowings')) {
            return 0.0;
        }
        try {
            return (float) $this->activeBorrowings()->sum('fine_amount');
        } catch (\Exception $e) {
            return 0.0;
        }
    }

    // Latest borrowing method
    public function latestBorrowing()
    {
        if (!class_exists('\App\Models\Borrowing') || !Schema::hasTable('borrowings')) {
            return $this->hasOne(\App\Models\User::class)->where('id', 0); // Empty relation
        }
        return $this->hasOne(\App\Models\Borrowing::class)->latest('borrowed_date');
    }

    // Status methods
    public function getBorrowingStatus(): string
    {
        try {
            if ($this->hasOverdueBooks()) {
                return 'overdue';
            } elseif ($this->getCurrentBorrowedCount() > 0) {
                return 'active';
            } else {
                return 'inactive';
            }
        } catch (\Exception $e) {
            return 'inactive';
        }
    }

    public function getBorrowingSummary(): string
    {
        try {
            $active = $this->getCurrentBorrowedCount();
            $overdue = $this->getOverdueCount();
            $total = $this->getTotalBorrowedCount();

            if ($overdue > 0) {
                return "{$active} active ({$overdue} overdue) • {$total} total";
            } elseif ($active > 0) {
                return "{$active} active • {$total} total";
            } else {
                return $total > 0 ? "{$total} books borrowed (none active)" : 'No borrowing history';
            }
        } catch (\Exception $e) {
            return 'No borrowing history';
        }
    }

    // Scopes
    public function scopeStudents($query)
    {
        return $query->where('role', 'user');
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeWithActiveBorrowings($query)
    {
        if (!class_exists('\App\Models\Borrowing') || !Schema::hasTable('borrowings')) {
            return $query->where('id', 0); // Return empty result
        }
        return $query->whereHas('activeBorrowings');
    }

    public function scopeWithOverdueBooks($query)
    {
        if (!class_exists('\App\Models\Borrowing') || !Schema::hasTable('borrowings')) {
            return $query->where('id', 0); // Return empty result
        }
        return $query->whereHas('overdueBorrowings');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");

            if (Schema::hasColumn('users', 'phone')) {
                $q->orWhere('phone', 'like', "%{$search}%");
            }

            if (Schema::hasColumn('users', 'student_id')) {
                $q->orWhere('student_id', 'like', "%{$search}%");
            }
        });
    }

    // Attribute accessors
    public function getFullNameAttribute(): string
    {
        return $this->student_id
            ? "{$this->name} (ID: {$this->student_id})"
            : $this->name;
    }

    public function getInitialsAttribute(): string
    {
        $names = explode(' ', $this->name);
        $initials = '';

        foreach ($names as $name) {
            $initials .= strtoupper(substr($name, 0, 1));
        }

        return substr($initials, 0, 2);
    }

    public function getUserStatsAttribute(): array
    {
        return [
            'borrowed_books' => $this->getCurrentBorrowedCount(),
            'due_soon' => $this->getDueSoonCount(),
            'overdue' => $this->getOverdueCount(),
            'favorites' => $this->getFavoritesCount(),
            'total_read' => $this->getTotalReturnedCount(),
            'fine_amount' => $this->getOutstandingFineAmount(),
        ];
    }

    public function getRoleColorAttribute(): string
    {
        return $this->role === 'admin' ? 'danger' : 'primary';
    }

    public function getRoleBadgeAttribute(): string
    {
        return $this->role === 'admin' ? 'Admin' : 'Student';
    }

    public function getStatusColorAttribute(): string
    {
        $status = $this->getBorrowingStatus();
        return match($status) {
            'overdue' => 'danger',
            'active' => 'success',
            'inactive' => 'secondary',
            default => 'secondary'
        };
    }

    public function getAvatarUrlAttribute(): string
    {
        return "https://ui-avatars.com/api/?name={$this->initials}&background=random&color=fff&size=100";
    }

}
