<?php
/**
 * SAM-6481: Add bidding options on the admin - auction - lots - added lots table
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 23, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\PlaceBid;

use Auction;
use InvalidArgumentException;
use Sam\Application\Controller\Admin\PlaceBid\LiveOrHybrid\LiveOrHybridAbsenteeBidValidatorCreateTrait;
use Sam\Application\Controller\Admin\PlaceBid\Timed\TimedBidValidatorCreateTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class is responsible to detect and construct applicable place bid command validator
 *
 * Class PlaceBidValidatorProvider
 * @package Sam\Application\Controller\Admin\PlaceBid
 */
class PlaceBidValidatorProvider extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use LiveOrHybridAbsenteeBidValidatorCreateTrait;
    use TimedBidValidatorCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Detect and construct appropriate place bid command validator for the auction
     *
     * @param int $auctionId
     * @return PlaceBidValidatorInterface
     */
    public function detectForAuction(int $auctionId): PlaceBidValidatorInterface
    {
        if ($this->loadAuction($auctionId)->isTimed()) {
            $validator = $this->createTimedBidValidator()->construct();
        } else {
            $validator = $this->createLiveOrHybridAbsenteeBidValidator();
        }
        return $validator;
    }

    /**
     * @param int $auctionId
     * @return Auction
     */
    protected function loadAuction(int $auctionId): Auction
    {
        $auction = $this->getAuctionLoader()->load($auctionId);
        if (!$auction) {
            throw new InvalidArgumentException('Available auction not found ' . composeSuffix(['a' => $auctionId]));
        }
        return $auction;
    }
}
