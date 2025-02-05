<?php

namespace AhmedArafat\AllInOne\Traits;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rules\Unique;

trait CustomRuleTraits
{
    /**
     * @param int $min
     * @param bool $hasMixed
     * @param bool $hasNumbers
     * @param bool $hasSymbols
     * @param bool $uncompromised
     * @return Password
     */
    public function strongPassword(int $min = 8, bool $hasMixed = true, bool $hasNumbers = true, bool $hasSymbols = true, bool $uncompromised = true): Password
    {
        $passwordRule = Password::min($min);
        if ($hasMixed) $passwordRule->mixedCase();
        if ($hasNumbers) $passwordRule->numbers();
        if ($hasSymbols) $passwordRule->symbols();
        if ($uncompromised) $passwordRule->uncompromised();
        return $passwordRule;
    }

    /**
     * @param string $table
     * @param string $column
     * @param $whereClosure
     * @return Exists
     */
    public function existsRule(string $table, string $column = 'id', $whereClosure = null): Exists
    {
        $existsRule = Rule::exists($table, $column);
        if ($whereClosure) $existsRule->where($whereClosure);
        return $existsRule;
    }

    /**
     * @param string $table
     * @param string $column
     * @param $whereClosure
     * @param $ignoreId
     * @return Unique
     */
    public function uniqueRule(string $table, string $column = 'id', $whereClosure = null, $ignoreId = null): Unique
    {
        $uniqueRule = Rule::unique($table, $column);
        if ($whereClosure) $uniqueRule->where($whereClosure);
        if ($ignoreId) $uniqueRule->ignore($ignoreId);
        return $uniqueRule;
    }
}
