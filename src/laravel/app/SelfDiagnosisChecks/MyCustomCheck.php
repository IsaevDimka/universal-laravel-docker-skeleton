<?php


namespace App\SelfDiagnosisChecks;

use BeyondCode\SelfDiagnosis\Checks\Check;

class MyCustomCheck implements Check
{
    /**
     * The name of the check.
     *
     * @param array $config
     * @return string
     */
    public function name(array $config): string
    {
        return 'My custom check.';
    }

    /**
     * Perform the actual verification of this check.
     *
     * @param array $config
     * @return bool
     */
    public function check(array $config): bool
    {
        return true;
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @param array $config
     * @return string
     */
    public function message(array $config): string
    {
        return 'This is the error message that users see if "check" returns false.';
    }
}
