<?php

namespace App\Enums;

enum BudgetStatus: string
{
    case SAFE = 'safe';
    case WARNING = 'warning';
    case CRITICAL = 'critical';
    case OVER_BUDGET = 'over_budget';
}
