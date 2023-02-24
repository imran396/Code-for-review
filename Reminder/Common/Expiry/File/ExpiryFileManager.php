<?php
/**
 * SAM-9734: Fix email reminder behavior for the case when last run timestamps are missed
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 18, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Reminder\Common\Expiry\File;

use DateInterval;
use DateTime;
use Sam\Core\Path\PathResolver;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\Core\Constants;

/**
 * Class ExpiryFileManager
 * @package
 */
class ExpiryFileManager extends CustomizableClass
{
    use FileManagerCreateTrait;
    use CurrentDateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function readLastRunTimeOrInitByFrequency(string $fileRootPath, int $emailFrequency): string
    {
        return $this->readLastRunTimeOrInitByFn(
            $fileRootPath,
            function () use ($emailFrequency) {
                $dateInterval = new DateInterval('PT' . ($emailFrequency + 1) . 'H');
                $initialRunTime = $this->getCurrentDateUtc()
                    ->sub($dateInterval)
                    ->format(Constants\Date::ISO);
                return $initialRunTime;
            }
        );
    }

    public function readLastRunTimeOrInitByCurrentDate(string $fileRootPath): string
    {
        return $this->readLastRunTimeOrInitByFn(
            $fileRootPath,
            function () {
                return $this->getCurrentDateUtc()->format(Constants\Date::ISO);
            }
        );
    }

    protected function readLastRunTimeOrInitByFn(string $fileRootPath, callable $initFn): string
    {
        $fileManager = $this->createFileManager();
        // Create file with current date time when file does not exists
        if (!$fileManager->exist($fileRootPath)) {
            $fileManager->write($initFn(), $fileRootPath);
        }
        //Read last run time
        return $fileManager->read($fileRootPath);
    }

    public function writeLastRun(
        string $fileRootPath,
        ?DateTime $lastRunUtc,
        ?DateTime $reminderLastRun
    ): bool {
        if (
            !$reminderLastRun
            || !$lastRunUtc
            || $reminderLastRun == $lastRunUtc
        ) {
            return false;
        }

        // Write last run timestamp
        $fileManager = $this->createFileManager();
        $fileManager->write($lastRunUtc->format(Constants\Date::ISO), $fileRootPath);
        return true;
    }

    public function makeFileRootPath(string $fileName): string
    {
        return sprintf('%s/%s', PathResolver::LOG_RUN, $fileName);
    }

}
