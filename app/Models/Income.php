<?php

namespace App\Models;

use App\Enums\RecurrenceType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Income extends Model
{
    protected $fillable = [
        'user_id', 'source', 'amount', 'date', 'category_id',
        'description', 'is_recurring', 'recurrence_type',
        'remote_id', 'is_synced'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
        'is_recurring' => 'boolean',
        'recurrence_type' => RecurrenceType::class,
        'is_synced' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Scopes
    public function scopeForMonth($query, int $year, int $month)
    {
        return $query->whereYear('date', $year)
                     ->whereMonth('date', $month);
    }

    public function scopeRecurring($query)
    {
        return $query->where('is_recurring', true);
    }

    public function scopeBySource($query, string $source)
    {
        return $query->where('source', $source);
    }

    // Helpers
    public function getFormattedAmount(): string
    {
        return '₦' . number_format($this->amount, 2);
    }

    public function isCurrentMonth(): bool
    {
        return $this->date->format('Y-m') === now()->format('Y-m');
    }
}
