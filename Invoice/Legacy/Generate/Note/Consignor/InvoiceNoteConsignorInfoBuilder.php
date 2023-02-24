<?php
/**
 * SAM-4377: Invoice producer
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           15.01.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\Legacy\Generate\Note\Consignor;

use Sam\Core\Service\CustomizableClass;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Invoice\Common\Load\InvoiceItem\InvoiceItemLoaderAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class InvoiceNoteConsignorInfoBuilder
 * @package Sam\Invoice\Legacy\Generate\Note\Consignor
 */
class InvoiceNoteConsignorInfoBuilder extends CustomizableClass
{
    use AuctionLotLoaderAwareTrait;
    use CurrencyLoaderAwareTrait;
    use DataLoaderAwareTrait;
    use InvoiceItemLoaderAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotRendererAwareTrait;
    use NumberFormatterAwareTrait;
    use SystemAccountAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Build consignor's notes for provided invoice
     *
     * @param int $invoiceId
     * @return string
     */
    public function build(int $invoiceId): string
    {
        $currencySign = $this->getCurrencyLoader()->detectDefaultSign();

        $note = '';
        $consignorInfos = [];

        $invoiceItemsWithConsignorInfo = $this->getDataLoader()->load($invoiceId);

        foreach ($invoiceItemsWithConsignorInfo as $row) {
            $hammerPrice = $row['hammer_price'];
            $salesTax = $hammerPrice * ($row['sales_tax'] / 100);
            $subTotal = $hammerPrice + $salesTax;
            $consignorUserId = $row['consignor_id'];
            $consignorUsername = $row['consignor_username'];
            $consignorPaymentInfo = $row['consignor_payment_info'];
            $lotName = $row['lot_name'];

            $auctionLot = $this->getAuctionLotLoader()
                ->load((int)$row['lot_item_id'], (int)$row['auction_id'], true);
            $lotNo = $this->getLotRenderer()->renderLotNo($auctionLot);

            if (!empty($consignorPaymentInfo)) {
                $consignorInfos[$consignorUserId]['account_id'] = (int)$row['account_id'];
                $consignorInfos[$consignorUserId]['consignor_name'] = $consignorUsername;
                $consignorInfos[$consignorUserId]['currency'] = $currencySign;
                if (!isset($consignorInfos[$consignorUserId]['totals'])) {
                    $consignorInfos[$consignorUserId]['totals'] = $subTotal;
                } else {
                    $consignorInfos[$consignorUserId]['totals'] += $subTotal;
                }

                $consignorInfos[$consignorUserId]['payment_info'] = $consignorPaymentInfo;

                if (!isset($consignorInfos[$consignorUserId]['lots_purchased'])) {
                    $consignorInfos[$consignorUserId]['lots_purchased'] = sprintf(
                        "- %s (Lot %s)\n",
                        $lotName,
                        $lotNo
                    );
                } else {
                    $consignorInfos[$consignorUserId]['lots_purchased'] .= sprintf(
                        "- %s (Lot %s)\n",
                        $lotName,
                        $lotNo
                    );
                }
            }
        }

        foreach ($consignorInfos as $consignorInfo) {
            $totalsFormatted = $this->getNumberFormatter()->formatMoney($consignorInfo['totals'], $consignorInfo['account_id']);
            $note .= "Total owed to consignor {$consignorInfo['consignor_name']}: "
                . $consignorInfo['currency'] . $totalsFormatted . "\n"
                . "Lots purchased: \n"
                . $consignorInfo['lots_purchased']
                . "Payment information:\n"
                . $consignorInfo['payment_info'] . "\n\n";
        }

        return $note;
    }
}
