<?php
/**
 * SAM-9734: Fix email reminder behavior for the case when last run timestamps are missed
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Reminder\Common\Expiry\Validate;

use DateInterval;
use DateTime;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class ExpirationDateChecker
 * @package Sam\Reminder
 */
class ExpirationDateChecker extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function isExpiredTimeoutAndFrequencySet(
        DateTime $currentDateUtc,
        ?DateTime $lastRunDateUtc,
        int $emailFrequency,
        string $name
    ): bool {
        $isExpiredTimeout = $this->isExpiredTimeout($currentDateUtc, $lastRunDateUtc, $emailFrequency);
        $isFrequencySet = $emailFrequency > 0;

        if (
            $isExpiredTimeout
            && $isFrequencySet
        ) {
            return true;
        }

        // if last run time plus frequency hours are bigger than current time
        $logData = [
            'Timeout expired' => (int)$isExpiredTimeout,
            'Frequency' => $emailFrequency,
        ];
        $message = sprintf('%s reminder: Missing sending reminder emails due to not matching frequency settings', $name);
        log_warning($message . composeSuffix($logData));
        return false;
    }

    /**
     * @param DateTime $currentDateUtc
     * @param DateTime|null $lastRunDateUtc
     * @param int $emailFrequency
     * @return bool
     * #[Pure]
     */
    protected function isExpiredTimeout(
        DateTime $currentDateUtc,
        ?DateTime $lastRunDateUtc,
        int $emailFrequency
    ): bool {
        if (!$lastRunDateUtc) {
            return true;
        }

        $dttLastRunPlusFreq = clone $lastRunDateUtc;
        $dttLastRunPlusFreq->add(new DateInterval('PT' . $emailFrequency . 'H'));
        return $currentDateUtc > $dttLastRunPlusFreq;
    }

    public function checkLastRun(
        DateTime $currentDateUtc,
        ?DateTime $lastRunDateUtc,
        int $scriptInterval,
        string $name
    ): bool {
        $lastRun = $this->getLastRun($currentDateUtc, $lastRunDateUtc, $scriptInterval);
        if (!$lastRun) {
            // if there is no last run timestamp and the interval is not set, stop processing
            log_warning(sprintf('Missing LastRun timestamp or ScriptInterval to process %s reminders', $name));
            return false;
        }

        $logData = [
            'Current time' => $currentDateUtc->format(Constants\Date::ISO),
            'Last run' => $lastRun,
        ];
        log_debug(composeLogData($logData));
        return true;
    }

    /**
     * @param DateTime $currentDateUtc
     * @param DateTime|null $lastRunDateUtc
     * @param int $scriptInterval
     * @return string
     * #[Pure]
     */
    public function getLastRun(
        DateTime $currentDateUtc,
        ?DateTime $lastRunDateUtc,
        int $scriptInterval
    ): string {
        if (isset($lastRunDateUtc)) {
            return $lastRunDateUtc->format(Constants\Date::ISO);
        }

        if ($scriptInterval) {
            // if there is no last run timestamp and the interval is set,
            // use that to calculate "Last Run" time
            $lastRunDateUtc = $currentDateUtc;
            // subtract script interval hours
            return $lastRunDateUtc
                ->sub(new DateInterval('PT' . $scriptInterval . 'H'))
                ->format(Constants\Date::ISO);
        }

        return '';
    }
}
