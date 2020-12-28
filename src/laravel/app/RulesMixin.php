<?php

namespace App;

use Carbon\Carbon;

/**
 * Class RulesMixin
 *
 * @package App\RulesMixin
 *
 * Example using:
$this->validate($request, [
    'date' => [Rule::before($model->created_at)],
]);
 */

class RulesMixin
{
    public function before()
    {
        return function(Carbon $date) {
            return 'before:' . $date->toDateTimeString();
        };
    }
    public function beforeOrEqual()
    {
        return function(Carbon $date) {
            return 'before_or_equal:' . $date->toDateTimeString();
        };
    }
    public function after()
    {
        return function(Carbon $date) {
            return 'after:' . $date->toDateTimeString();
        };
    }
    public function afterOrEqual()
    {
        return function(Carbon $date) {
            return 'after_or_equal:' . $date->toDateTimeString();
        };
    }
}