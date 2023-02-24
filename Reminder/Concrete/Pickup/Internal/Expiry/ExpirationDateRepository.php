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

namespace Sam\Reminder\Concrete\Pickup\Internal\Expiry;

use DateTime;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Reminder\Common\Expiry\File\ExpiryFileManagerCreateTrait;

/**
 * Class ExpirationDateRepository
 */
class ExpirationDateRepository extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use ExpiryFileManagerCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Read last run date from the file. It is expected to be in UTC timezone.
     * @param int $emailFrequency
     * @return DateTime|null
     */
    public function readLastRunDateUtc(int $emailFrequency): ?DateTime
    {
        $dateIso = $this->createExpiryFileManager()->readLastRunTimeOrInitByFrequency(
            $this->makeFileRootPath(),
            $emailFrequency
        );
        $dateUtc = $dateIso ? new DateTime($dateIso) : null;
        return $dateUtc;
    }

    /**
     * Write last run date in the file. It is expected to be in UTC timezone.
     * @param DateTime|null $lastRunUtc
     * @param DateTime|null $reminderLastRunUtc
     */
    public function writeLastRun(?DateTime $lastRunUtc, ?DateTime $reminderLastRunUtc): void
    {
        $success = $this->createExpiryFileManager()->writeLastRun(
            $this->makeFileRootPath(),
            $lastRunUtc,
            $reminderLastRunUtc
        );
        $message = $success
            ? sprintf(
                'Pickup reminder last run timestamp changed from "%s" to "%s"',
                $reminderLastRunUtc ? $reminderLastRunUtc->format(Constants\Date::ISO) : '',
                $lastRunUtc ? $lastRunUtc->format(Constants\Date::ISO) : ''
            )
            : sprintf(
                'Pickup reminder last run timestamp kept the same "%s"',
                $reminderLastRunUtc ? $reminderLastRunUtc->format(Constants\Date::ISO) : ''
            );
        log_debug($message);
    }

    /**
     * Make absolute path to file with last run date.
     * @return string
     */
    public function makeFileRootPath(): string
    {
        return $this->createExpiryFileManager()
            ->makeFileRootPath($this->cfg()->get('core->reminder->pickup->lastRunFile'));
    }
}
