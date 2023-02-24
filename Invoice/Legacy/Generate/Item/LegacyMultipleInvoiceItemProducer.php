<?php
/**
 * SAM-4377: Invoice producer
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           04.01.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\Legacy\Generate\Item;

use Exception;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\Invoice\Legacy\Generate\Item\Single\LegacySingleInvoiceItemProducerAwareTrait;
use Sam\Invoice\Legacy\Generate\Item\Single\LegacySingleInvoiceItemProductionInput;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Storage\ReadRepository\Entity\InvoiceItem\InvoiceItemReadRepositoryCreateTrait;

class LegacyMultipleInvoiceItemProducer extends CustomizableClass
{
    use InvoiceItemReadRepositoryCreateTrait;
    use InvoiceLoaderAwareTrait;
    use LotItemLoaderAwareTrait;
    use LegacySingleInvoiceItemProducerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param LegacySingleInvoiceItemProductionInput[] $inputs
     * @return bool
     */
    public function produce(array $inputs): bool
    {
        $singleInvoiceProducer = $this->getLegacySingleInvoiceItemProducer();
        foreach ($inputs as $input) {
            try {
                $isFound = $this->createInvoiceItemReadRepository()
                    ->filterActive(true)
                    ->filterAuctionId($input->auctionId)
                    ->filterLotItemId($input->lotItem->Id)
                    ->filterRelease([false, null])
                    // ->joinAccountFilterActive(true)
                    // ->joinAuctionFilterAuctionStatusId(Constants\Auction::$notDeletedAuctionStatuses)
                    // ->joinAuctionLotItemFilterLotStatusId(Constants\Lot::$availableLotStatuses)
                    // ->joinLotItemFilterActive(true)
                    // ->joinInvoiceFilterInvoiceStatusId(Constants\Invoice::$availableInvoiceStatuses)
                    // ->joinUserWinningBidderFilterUserStatusId(Constants\User::US_ACTIVE)
                    ->exist();
                if (!$isFound) {
                    $singleInvoiceProducer->produce($input);
                } else {
                    log_warning(
                        'Already in invoice lot item with auction'
                        . composeSuffix(
                            [
                                'li' => $input->lotItem->Id,
                                'a' => $input->auctionId,
                            ]
                        )
                    );
                    return false;
                }
            } catch (Exception $e) {
                log_warning(
                    'Failed to add in lot item with auction'
                    . composeSuffix(
                        [
                            'error' => $e->getMessage(),
                            'li' => $input->lotItem->Id,
                            'a' => $input->auctionId,
                        ]
                    )
                );
                return false;
            }
        }
        return true;
    }
}
