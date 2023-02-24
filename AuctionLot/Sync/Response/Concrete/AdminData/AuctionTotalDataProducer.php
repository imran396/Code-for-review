<?php
/**
 * SAM-6573: Refactor lot list data sync providers - structurize responses
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 29, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Sync\Response\Concrete\AdminData;

use AuctionCache;
use Sam\Auction\Cache\AuctionDbCacheManagerAwareTrait;
use Sam\Auction\Load\AuctionCacheLoaderAwareTrait;
use Sam\AuctionLot\Sync\Response\Concrete\AdminData\Generated\Message\AuctionTotals;
use Sam\AuctionLot\Sync\Response\Concrete\AdminData\Internal\AuctionTotalsMessageFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Installation\Config\Repository\ConfigRepository;

/**
 * Provides up-to-date aggregated data for the auction lots, which is used to synchronize totals at admin lots list page
 *
 * Class AuctionTotalDataProducer
 * @package Sam\AuctionLot\Sync\Response\Concrete\AdminData\Internal
 */
class AuctionTotalDataProducer extends CustomizableClass
{
    use AuctionCacheLoaderAwareTrait;
    use AuctionDbCacheManagerAwareTrait;
    use AuctionTotalsMessageFactoryCreateTrait;
    use OptionalsTrait;

    public const OP_ADMIN_AUCTION_LOTS_SUMMARY_ALWAYS_ACTUAL = 'adminAuctionLotsSummaryAlwaysActual';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals = [
     *         OP_ADMIN_AUCTION_LOTS_SUMMARY_ALWAYS_ACTUAL => (bool)
     *      ]
     * @return static
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * Collect the aggregated data for the auction lots and put it in the protobuf message object
     *
     * @param int $auctionId
     * @param int $editorUserId
     * @param bool $isProfilingEnabled
     * @return AuctionTotals
     */
    public function produce(int $auctionId, int $editorUserId, bool $isProfilingEnabled = false): AuctionTotals
    {
        $tmpTs = microtime(true);
        $auctionCache = $this->fetchAuctionCache($auctionId, $editorUserId);

        if ($isProfilingEnabled) {
            log_debug(composeSuffix(['AuctionCache load time' => ((microtime(true) - $tmpTs) * 1000) . 'ms']));
        }

        $message = $this->createAuctionTotalsMessageFactory()->create($auctionCache);
        return $message;
    }

    /**
     * @param int $auctionId
     * @param int $editorUserId
     * @return AuctionCache
     */
    protected function fetchAuctionCache(int $auctionId, int $editorUserId): AuctionCache
    {
        $auctionCache = $this->getAuctionCacheLoader()->load($auctionId);
        if (
            !$auctionCache
            || (
                $this->fetchOptional(self::OP_ADMIN_AUCTION_LOTS_SUMMARY_ALWAYS_ACTUAL)
                && !$auctionCache->CalculatedOn
            )
        ) {
            $auctionCache = $this->getAuctionDbCacheManager()->refreshByAuctionId($auctionId, $editorUserId);
        }
        return $auctionCache;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_ADMIN_AUCTION_LOTS_SUMMARY_ALWAYS_ACTUAL] = $optionals[self::OP_ADMIN_AUCTION_LOTS_SUMMARY_ALWAYS_ACTUAL]
            ?? static function () {
                return ConfigRepository::getInstance()->get('core->admin->auction->lots->summary->alwaysActual');
            };
        $this->setOptionals($optionals);
    }
}
