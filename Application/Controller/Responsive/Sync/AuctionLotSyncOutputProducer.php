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

use Sam\AuctionLot\Sync\PublicDataProvider;
use Sam\Core\Service\CustomizableClass;
use Sam\Infrastructure\Profiling\Web\WebProfilingLogger;
use Sam\Infrastructure\Profiling\Web\WebProfilingLoggerCreateTrait;

/**
 * Class AuctionLotSyncOutputProducer
 * @package Sam\Application\Controller\Responsive\Sync
 */
class AuctionLotSyncOutputProducer extends CustomizableClass
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

    public function produce(array $auctionLotIds, int $accountId, ?int $editorUserId, bool $isProfilingEnabled = false): void
    {
        $scriptStartTs = microtime(true);
        PublicDataProvider::new()->run($accountId, $editorUserId, $auctionLotIds, $isProfilingEnabled);

        $this->createWebProfilingLogger()
            ->construct([WebProfilingLogger::OP_PROFILING_ENABLED => $isProfilingEnabled])
            ->log($scriptStartTs, 'Profiling lot list sync');
    }
}
