<?php
/**
 * SAM-10903: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract the Release Lot action
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\ReleaseLot;

use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Validate\State\LotStateDetectorCreateTrait;
use Sam\Storage\ReadRepository\Entity\InvoiceItem\InvoiceItemReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\LotItem\LotItemReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\InvoiceItem\InvoiceItemWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\LotItem\LotItemWriteRepositoryAwareTrait;

/**
 * Class InvoiceLotReleaser
 * @package Sam\Invoice\Legacy\ReleaseLot
 */
class LegacyInvoiceLotReleaser extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use AuctionLotItemWriteRepositoryAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use InvoiceItemReadRepositoryCreateTrait;
    use InvoiceItemWriteRepositoryAwareTrait;
    use LotItemReadRepositoryCreateTrait;
    use LotItemWriteRepositoryAwareTrait;
    use LotStateDetectorCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $invoiceItemIds
     * @param int $editorUserId
     * @return void
     */
    public function release(array $invoiceItemIds, int $editorUserId): void
    {
        $invoiceItems = $this->createInvoiceItemReadRepository()
            ->filterId($invoiceItemIds)
            // ->joinAccountFilterActive(true)
            // ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
            // ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
            // ->joinLotItemFilterActive(true)
            // ->joinInvoiceFilterInvoiceStatusId(Constants\Invoice::$availableInvoiceStatuses)
            // ->joinUserWinningBidderFilterUserStatusId(Constants\User::US_ACTIVE)
            ->loadEntities();

        $lotItemIds = [];
        if ($invoiceItems) {
            foreach ($invoiceItems as $invoiceItem) {
                $lotItemIds[] = $invoiceItem->LotItemId;
                $invoiceItem->HammerPrice = null;
                $invoiceItem->WinningBidderId = null;
                $invoiceItem->Subtotal -= $invoiceItem->HammerPrice;
                $this->getInvoiceItemWriteRepository()->saveWithModifier($invoiceItem, $editorUserId);
            }
        }

        if ($lotItemIds) {
            $lotItems = $this->createLotItemReadRepository()
                ->filterId($lotItemIds)
                ->loadEntities();

            if ($lotItems) {
                foreach ($lotItems as $lotItem) {
                    $auctionId = $lotItem->AuctionId;
                    $lotItem->wipeOutSoldInfo();
                    $this->getLotItemWriteRepository()->saveWithModifier($lotItem, $editorUserId);

                    $auctionLot = $this->getAuctionLotLoader()->load($lotItem->Id, $auctionId);
                    if ($auctionLot) {
                        $isUnsold = false;
                        $auction = $this->getAuctionLoader()->load($auctionId);
                        if ($auction) {
                            if ($auction->isTimed()) {
                                $isLotEnded = $this->createLotStateDetector()->isLotEnded($auctionLot);
                                if ($isLotEnded) {
                                    $isUnsold = true;
                                }
                            } elseif ($auction->isClosed()) {
                                $isUnsold = true;
                            }
                        }
                        if ($isUnsold) {
                            $auctionLot->toUnsold();
                        } else {
                            $auctionLot->toActive();
                        }

                        $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $editorUserId);
                    }
                }
            }
        }
    }
}
