<?php

namespace App\Enums;

enum BudgetAlertType: string
{
    case THRESHOLD_80 = 'threshold_80';
    case THRESHOLD_100 = 'threshold_100';
    case OVER_BUDGET = 'over_budget';
}
