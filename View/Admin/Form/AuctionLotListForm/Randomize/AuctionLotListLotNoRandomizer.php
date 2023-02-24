<?php
/**
 * SAM-6780: Move sections' logic to separate Panel classes at Manage auction lots page
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\Randomize;

use Auction;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\AuctionLot\Order\Reorder\AuctionLotReordererAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;

/**
 * Class AuctionLotListRandomizer
 * @package
 */
class AuctionLotListLotNoRandomizer extends CustomizableClass
{
    use AuctionLotItemWriteRepositoryAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use AuctionLotReordererAwareTrait;
    use OptionalsTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals
     * @return $this
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * @param Auction $auction
     * @param array $lotNums
     * @param int $editorUserId
     */
    public function randomize(Auction $auction, array $lotNums, int $editorUserId): void
    {
        $ctr = 0;
        $newLotNums = $lotNums;
        shuffle($newLotNums);
        $auctionLotWriteRepo = $this->getAuctionLotItemWriteRepository();
        foreach ($lotNums as $auctionLotId => $lotItem) {
            $auctionLot = $this->getAuctionLotLoader()->loadById($auctionLotId, true);
            if (!$auctionLot) {
                log_error(
                    "Available auction lot not found when randomizing lot#s"
                    . composeSuffix(['ali' => $auctionLotId])
                );
                continue;
            }
            $auctionLot->LotNum = $newLotNums[$ctr];
            $auctionLotWriteRepo->saveWithModifier($auctionLot, $editorUserId);
            $ctr++;
        }
        $this->getAuctionLotReorderer()->reorder($auction, $editorUserId);
    }

    /**
     * Initialize optionals value-dependencies of service
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $this->setOptionals($optionals);
    }
}
