<?php

namespace AhmedArafat\AllInOne\Traits;

use Illuminate\Support\Carbon;

trait DateHelperTrait
{
    public function getDifferenceBetweenYearsFormat($startDate, $endDate, $yearsFormat = 'yrs', $monthFormat = 'mo')
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        $years = $startDate->diffInYears($endDate);
        $months = $startDate->copy()->addYears($years)->diffInMonths($endDate);
        return sprintf("%d$yearsFormat %02d$monthFormat", $years, $months);
    }
}
