<?php
/**
 * Created by PhpStorm.
 * User: igor
 * Date: 20.02.2016
 * Time: 16:15
 */

namespace Sam\Core\Date;

use DateInterval;

/**
 * The stock DateInterval never got the patch to compare.
 * Let's re-implement the patch in user space.
 * See the original patch at http://www.adamharvey.name/patches/DateInterval-comparators.patch
 */
class ComparableDateInterval extends DateInterval
{
    /**
     * @param DateInterval $oDateInterval
     * @return self
     */
    public static function create(DateInterval $oDateInterval): self
    {
        $oDi = new self('P1D');
        $oDi->s = $oDateInterval->s;
        $oDi->i = $oDateInterval->i;
        $oDi->h = $oDateInterval->h;
        $oDi->days = $oDateInterval->days;
        $oDi->d = $oDateInterval->d;
        $oDi->m = $oDateInterval->m;
        $oDi->y = $oDateInterval->y;
        $oDi->invert = $oDateInterval->invert;

        return $oDi;
    }

    /**
     * @param ComparableDateInterval $oDateInterval
     * @return int
     */
    public function compare(ComparableDateInterval $oDateInterval): int
    {
        $oMyTotalSeconds = $this->getTotalSeconds();
        $oYourTotalSeconds = $oDateInterval->getTotalSeconds();
        if ($oMyTotalSeconds < $oYourTotalSeconds) {
            return -1;
        }
        if ($oMyTotalSeconds === $oYourTotalSeconds) {
            return 0;
        }
        return 1;
    }

    /**
     * If $this->days has been calculated, we know it's accurate, so we'll use
     * that. If not, we need to make an assumption about month and year length,
     * which isn't necessarily a good idea. I've defined months as 30 days and
     * years as 365 days completely out of thin air, since I don't have the ISO
     * 8601 spec available to check if there's a standard assumption, but we
     * may in fact want to error out if we don't have $this->days available.
     * @return int
     */
    public function getTotalSeconds(): int
    {
        $iSeconds = $this->s + ($this->i * 60) + ($this->h * 3600);

        if ($this->days > 0) {
            $iSeconds += ($this->days * 86400);
        } // @note Maybe you prefer to throw an Exception here per the note above
        else {
            $iSeconds += ($this->d * 86400) + ($this->m * 2592000) + ($this->y * 31536000);
        }

        if ($this->invert) {
            $iSeconds *= -1;
        }

        return $iSeconds;
    }
}
