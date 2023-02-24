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

namespace Sam\Rtb\State;

use Sam\Core\Service\CustomizableClass;
use RtbCurrent;
use Sam\Bidding\CurrentBid\HighBidDetectorCreateTrait;
use Sam\Rtb\AbsenteeBid\AutoplaceAbsenteeBidDetectorCreateTrait;
use Sam\Rtb\AskingBid\RtbAskingBidUpdaterCreateTrait;
use Sam\Rtb\Catalog\Bidder\Manage\BidderCatalogManagerFactoryCreateTrait;
use Sam\Rtb\Increment\Calculate\RtbIncrementDetectorCreateTrait;
use Sam\Rtb\Increment\Save\RtbIncrementUpdaterCreateTrait;
use Sam\Rtb\LotInfo\LotInfoServiceAwareTrait;

/**
 * Class RtbStateUpdater
 * @package Sam\Rtb\State
 */
class RtbStateUpdater extends CustomizableClass
{
    use AutoplaceAbsenteeBidDetectorCreateTrait;
    use BidderCatalogManagerFactoryCreateTrait;
    use HighBidDetectorCreateTrait;
    use LotInfoServiceAwareTrait;
    use RtbAskingBidUpdaterCreateTrait;
    use RtbIncrementDetectorCreateTrait;
    use RtbIncrementUpdaterCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Update `rtb_current` state. Don't persist.
     * @param RtbCurrent $rtbCurrent
     * @param int $systemAccountId
     * @param int $viewLanguageId
     * @return RtbCurrent
     */
    public function update(RtbCurrent $rtbCurrent, int $systemAccountId, int $viewLanguageId): RtbCurrent
    {
        $currentBid = $this->createHighBidDetector()->detectAmount($rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
        $rtbCurrent = $this->createRtbIncrementUpdater()->update($rtbCurrent, $currentBid);

        if (!$this->createRtbAskingBidUpdater()->hasRunningAskingBid($rtbCurrent)) {
            $rtbCurrent = $this->createRtbAskingBidUpdater()->update($rtbCurrent, $currentBid);
        }

        $rtbCurrent = $this->updatePendingBidder($rtbCurrent);

        $this->getLotInfoService()->create($rtbCurrent);

        $catalogManager = $this->createCatalogManagerFactory()
            ->createByRtbCurrent($rtbCurrent, $systemAccountId, $viewLanguageId);
        $catalogManager->create($rtbCurrent);

        return $rtbCurrent;
    }

    /**
     * Update pending bidder info in running rtb state. Don't persist in db.
     * @param RtbCurrent $rtbCurrent
     * @return RtbCurrent
     */
    protected function updatePendingBidder(RtbCurrent $rtbCurrent): RtbCurrent
    {
        if (!$rtbCurrent->NewBidBy) {
            $bidByUserId = $this->createAutoplaceAbsenteeBidDetector()
                ->setLotItemId($rtbCurrent->LotItemId)
                ->setAuctionId($rtbCurrent->AuctionId)
                ->setAskingBid($rtbCurrent->AskBid)
                ->detectUserId();
            if ($bidByUserId) {
                $rtbCurrent->NewBidBy = $bidByUserId;
                $rtbCurrent->AbsenteeBid = true;
            }
        }
        return $rtbCurrent;
    }
}
