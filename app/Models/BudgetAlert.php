<?php

namespace App\Models;

use App\Enums\BudgetAlertType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetAlert extends Model
{
    protected $fillable = [
        'budget_id', 'alert_type', 'triggered_at',
        'is_dismissed', 'spent_amount', 'budget_amount'
    ];

    protected $casts = [
        'alert_type' => BudgetAlertType::class,
        'triggered_at' => 'datetime',
        'is_dismissed' => 'boolean',
        'spent_amount' => 'decimal:2',
        'budget_amount' => 'decimal:2',
    ];

    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_dismissed', false);
    }

    public function getPercentageUsed(): int
    {
        if ($this->budget_amount <= 0) return 0;
        return (int) (($this->spent_amount / $this->budget_amount) * 100);
    }

    public function getMessage(): string
    {
        $categoryName = $this->budget->category?->name ?? 'Overall Budget';
        
        return match ($this->alert_type) {
            BudgetAlertType::THRESHOLD_80 => "You've used 80% of your {$categoryName} budget",
            BudgetAlertType::THRESHOLD_100 => "You've reached your {$categoryName} budget limit",
            BudgetAlertType::OVER_BUDGET => "You're over budget for {$categoryName}",
        };
    }
}
