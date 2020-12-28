<?php


namespace App\Services;

/**
 * Class BudgetService
 * @package App\Services
 *
 * days =  кол-во дней бюджета (from - to)
 * amount = budget.amount
 * days = from - to (count day)
 *
 * total_spent =  Transactions where between (from,to) ->sum(amount)
 * current_amount =  amount бюджета (amount - total_spent)
 *
 * spent_today = transaction where between today
 * amount_today = current_amount / days - spent_today
 *
 */
class BudgetService
{

}