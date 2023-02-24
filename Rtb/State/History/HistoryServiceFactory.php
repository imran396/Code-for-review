<?php
/**
 * SAM-5400: Rtb state update refactoring
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/19/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\State\History;

use InvalidArgumentException;
use RtbCurrent;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Service\CustomizableClass;

/**
 * Class StateHistoryServiceFactory
 */
class HistoryServiceFactory extends CustomizableClass
{
    use AuctionLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param RtbCurrent $rtbCurrent
     * @return Hybrid\Service|Live\Service
     */
    public function create(RtbCurrent $rtbCurrent): Base\Service
    {
        $auction = $this->getAuctionLoader()->load($rtbCurrent->AuctionId);
        if (!$auction) {
            $message = "Available auction not found, when creating history service"
                . composeSuffix(['a' => $rtbCurrent->AuctionId]);
            log_error($message);
            throw new InvalidArgumentException($message);
        }
        return $this->createByAuctionType($auction->AuctionType);
    }

    /**
     * @param string $auctionType
     * @return Hybrid\Service|Live\Service
     */
    public function createByAuctionType(string $auctionType): Base\Service
    {
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        if ($auctionStatusPureChecker->isLive($auctionType)) {
            return Live\Service::new();
        }
        if ($auctionStatusPureChecker->isHybrid($auctionType)) {
            return Hybrid\Service::new();
        }
        throw new InvalidArgumentException(
            'Cannot create catalog service by auction type'
            . composeSuffix(['type' => $auctionType])
        );
    }

    /**
     * @param RtbCurrent $rtbCurrent
     * @return Base\Helper
     */
    public function createHelper(RtbCurrent $rtbCurrent): Base\Helper
    {
        return $this->create($rtbCurrent)->getHistoryHelper();
    }
}
