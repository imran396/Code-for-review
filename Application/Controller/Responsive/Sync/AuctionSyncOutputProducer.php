<?php
/**
 * SAM-10192: Move alone end-points to controllers
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 15, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Sync;

use Sam\Auction\Sync\PublicDataProvider;
use Sam\Core\Service\CustomizableClass;
use Sam\Infrastructure\Profiling\Web\WebProfilingLogger;
use Sam\Infrastructure\Profiling\Web\WebProfilingLoggerCreateTrait;

/**
 * Class AuctionSyncOutputProducer
 * @package Sam\Application\Controller\Responsive\Sync
 */
class AuctionSyncOutputProducer extends CustomizableClass
{
    use WebProfilingLoggerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int[] $auctionIds
     * @param int $accountId
     * @param string $timezone
     * @param bool $checkStartEnding
     * @param bool $checkEndDate
     * @param bool $checkListUpdated
     * @param bool $isProfilingEnabled
     */
    public function produce(
        array $auctionIds,
        int $accountId,
        string $timezone,
        bool $checkStartEnding,
        bool $checkEndDate,
        bool $checkListUpdated,
        bool $isProfilingEnabled = false
    ): void {
        $scriptStartTs = microtime(true);
        $syncDataProvider = PublicDataProvider::new();
        $syncDataProvider->enableEndDateCheck($checkEndDate);
        $syncDataProvider->enableProfiling($isProfilingEnabled);
        $syncDataProvider->enableStartEndingCheck($checkStartEnding);
        $syncDataProvider->enableUpdateList($checkListUpdated);
        $syncDataProvider->setAuctionIds($auctionIds);
        $syncDataProvider->setSystemAccountId($accountId);
        $syncDataProvider->setTz($timezone);
        $syncDataProvider->run();
        WebProfilingLogger::new()
            ->construct([WebProfilingLogger::OP_PROFILING_ENABLED => $isProfilingEnabled])
            ->log($scriptStartTs, 'Profiling auction list sync');
    }
}
