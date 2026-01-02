<?php

namespace App\Models;

use App\Enums\BudgetType;
use App\Enums\BudgetPeriodType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Budget extends Model
{
    protected $fillable = [
        'user_id', 'budget_type', 'category_id', 'amount',
        'period_type', 'start_date', 'end_date', 'is_recurring',
        'alert_at_80', 'alert_at_100', 'alert_over_budget',
        'enable_notifications', 'remote_id', 'is_synced'
    ];

    protected $casts = [
        'budget_type' => BudgetType::class,
        'period_type' => BudgetPeriodType::class,
        'amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_recurring' => 'boolean',
        'alert_at_80' => 'boolean',
        'alert_at_100' => 'boolean',
        'alert_over_budget' => 'boolean',
        'enable_notifications' => 'boolean',
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

    public function alerts(): HasMany
    {
        return $this->hasMany(BudgetAlert::class);
    }

    // Scopes
    public function scopeForMonth($query, int $year, int $month)
    {
        $monthStart = now()->setYear($year)->setMonth($month)->startOfMonth();
        $monthEnd = now()->setYear($year)->setMonth($month)->endOfMonth();

        return $query->where(function ($q) use ($monthStart, $monthEnd) {
            $q->where(function ($sub) use ($monthEnd) {
                $sub->where('is_recurring', true)
                    ->where('start_date', '<=', $monthEnd);
            })->orWhere(function ($sub) use ($monthStart, $monthEnd) {
                $sub->where('start_date', '<=', $monthEnd)
                    ->where(function ($s) use ($monthStart) {
                        $s->whereNull('end_date')
                          ->orWhere('end_date', '>=', $monthStart);
                    });
            });
        });
    }

    public function scopeOverall($query)
    {
        return $query->where('budget_type', BudgetType::OVERALL);
    }

    public function scopeCategory($query)
    {
        return $query->where('budget_type', BudgetType::CATEGORY);
    }
}
