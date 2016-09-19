<?php

namespace thgs\base\Utils;

use Debugbar;
use Carbon\Carbon;

class DateUtils
{
    public static function convertDate($date, $fromFormat = 'd/m/Y', $toFormat = 'Y-m-d')
    {
        return Carbon::createFromFormat($fromFormat, $date)->format($toFormat);
    }

    public static function daysOverlap($range1, $range2)
    {
        #Debugbar::info([$range1, $range2]);

        $dt1 = $range1[0];
        $dt2 = $range1[1];

        $dt3 = $range2[0];
        $dt4 = $range2[1];

        if (self::isIncludedInRange($dt1, [$dt3, $dt4]))
        {
            Debugbar::info('dt1-dt2='.$dt1->diffInDays($dt2, false));
            // edge case of dt1 and dt2 to be the same
            $isDatesTheSame = $dt1->diffInDays($dt2, false) == 0;
            if ($isDatesTheSame)
            {
                return 1;
            }

            // since dt1 is already included to determine if both dates are
            // included we just check for dt2 as well
            $isDatesFullyIncluded = self::isIncludedInRange($dt2, [$dt3, $dt4]);

#            Debugbar::info('fully included: '.($isDatesFullyIncluded ? 'yes' : 'no'));

            return ($isDatesFullyIncluded)
                // dates are fully included in Range so the result is their
                // own diff
                ? $dt1->diffInDays($dt2, false)
                // dt2 is not included, so it is a partial include case,
                // still we have from this period the dates from dt1 to finish
                : $dt1->diffInDays($dt4, false) + 1;
        }
        else
        {
            // date1 is not included, so its either only dt2 included (partial
            // case) or none. So, we check if dt2 is included

            $isDatesPartiallyIncluded = self::isIncludedInRange($dt2, [$dt3, $dt4]);

#            Debugbar::info('partially included: '.($isDatesPartiallyIncluded ? 'yes' : 'no'));

            return ($isDatesPartiallyIncluded)
                // we return the days from beginning of Range till the dt2
                ? $dt3->diffInDays($dt2, false)
                // else none of the dates overlap, so result should be 0
                : 0;
        }
    }


    public static function isIncludedInRange($dt, $range)
    {
        $a = $range[0]->diffInDays($dt, false);
        $b = $range[1]->diffInDays($dt, false);

        return ($a >= 0) && ($b <= 0);
    }

}
