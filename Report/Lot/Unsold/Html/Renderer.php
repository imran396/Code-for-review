<?php
/**
 * SAM-4687: Refactor "Unsold Lots" report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/28/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * IMPORTANT NOTE: Report any changes of format to your manager and in the ticket you are working on!
 * This might include adding, changing, or moving columns, modifying header names, modifying data or data format
 */

namespace Sam\Report\Lot\Unsold\Html;

use DateTime;
use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Date\CurrentDateTrait;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\DateHelperAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Lot\Render\Amount\LotAmountRendererFactoryCreateTrait;
use Sam\Report\Base\Csv\ReportToolAwareTrait;

/**
 * Class Renderer
 * @package Sam\Report\Lot\Unsold\Html
 */
class Renderer extends CustomizableClass
{
    use AccountLoaderAwareTrait;
    use AuctionRendererAwareTrait;
    use CurrentDateTrait;
    use DateHelperAwareTrait;
    use LotAmountRendererFactoryCreateTrait;
    use ReportToolAwareTrait;
    use TranslatorAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string[] $fields
     * @param array $rows
     * @param array $auctionRow
     * @return string
     */
    public function output(array $fields, array $rows, array $auctionRow): string
    {
        $output = '';
        $header = '';
        $body = '';
        $auctionHouse = $auctionRow['account_name'];
        $saleName = $this->saleNameRender($auctionRow);
        $saleDate = $this->saleDateRender($auctionRow);
        $printDate = $this->getCurrentDateSys();
        $printDateFormatted = $this->getDateHelper()->formattedDate($printDate);

        foreach ($fields as $fieldName) {
            $headerClass = 'th-' . strtolower($fieldName);
            $header .= <<<HEADER
            <th class="{$headerClass}">{$fieldName}</th>
HEADER;
        }

        $rowIndex = 0;
        foreach ($rows as $row) {
            $body .= <<<HTML
        <tr id="row{$rowIndex}" >
HTML;
            foreach ($fields as $fieldName) {
                $tdClass = 'td-' . strtolower($fieldName);
                $value = match ($fieldName) {
                    'LotNum' => $row['lot_num_prefix'] . $row['lot_num'] . $row['lot_num_ext'],
                    'Quantity' => $this->createLotAmountRendererFactory()
                        ->create((int)$row['account_id'])
                        ->makeQuantity(Cast::toFloat($row['quantity']), (int)$row['quantity_scale']),
                    'Name' => $row['name'],
                    'ReservePrice' => $row['reserve_price'],
                    'LowEstimate' => $row['low_estimate'],
                    'HighEstimate' => $row['high_estimate'],
                    'Consignor' => $row['username'],
                    'GeneralNote' => $row['general_note'],
                    'NoteToClerk' => $row['note_to_clerk'],
                    'ItemNum' => $row['item_num'] . $row['item_num_ext'],
                    'Description' => $row['description'],
                    'StartingBid' => $row['starting_bid'],
                    'Cost' => $row['cost'],
                    'ReplacementPrice' => $row['replacement_price'],
                    'SalesTax' => $row['sales_tax'],
                    default => '',
                };
                $body .= <<<HEADER
            <td class="{$tdClass}">{$value}</th>
HEADER;
            }
            $body .= <<<HTML
</tr>
HTML;
            $rowIndex++;
        }

        $output .= <<<HTML
<article class="bodybox">
    <ul class="viewinfo">
        <li>
            <div>
                Auction Name: {$saleName}<br />
                Auction Date: {$saleDate}<br />
            </div>
        </li>
        <li>
            <div>
                {$printDateFormatted}<br />
                {$auctionHouse}<br />
            </div>
        </li>
    </ul>
    <div class="tablesection">
        <table class="footable foolarge invoice-datagrid borderOne" id="c2" >
            <thead>
                <tr>
                    {$header}
                </tr>
            </thead>
            <tbody>
                {$body}
            </tbody>
        </table>
    </div>
    <div class="clear"></div>
</article>
<div class="clear"></div>
HTML;

        return $output;
    }

    /**
     * @param array $auctionRow
     * @return string
     */
    protected function saleNameRender(array $auctionRow): string
    {
        $auctionName = $this->getAuctionRenderer()->makeName($auctionRow['auction_name'], (bool)$auctionRow['test_auction']);
        $saleNo = $this->getAuctionRenderer()->makeSaleNo($auctionRow['sale_num'], $auctionRow['sale_num_ext']);
        $output = $auctionName . ' (' . $saleNo . ')';
        return $output;
    }

    /**
     * @param array $row
     * @return string
     */
    protected function saleDateRender(array $row): string
    {
        $auctionType = $row['auction_type'];
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        $date = $auctionStatusPureChecker->isTimed($auctionType)
            ? new DateTime($row['end_date'])
            : new DateTime($row['start_closing_date']);
        $date = $this->getDateHelper()->convertUtcToTzById($date, (int)$row['timezone_id']);
        $dateHtml =
            $auctionStatusPureChecker->isLiveOrHybrid($auctionType)
            || $auctionStatusPureChecker->isTimedScheduled($auctionType, (int)$row['event_type'])
                ? $this->getSaleDateHtml($date)
                : $this->getTranslator()->translate('AUCTIONS_EVENT_TYPE', 'auctions');
        $auctionId = (int)$row['auction_id'];
        $output = "<span id=\"lsaledate{$auctionId}\">{$dateHtml}</span>";
        return $output;
    }

    /**
     * @param DateTime $date
     * @return string
     */
    protected function getSaleDateHtml(DateTime $date): string
    {
        return $this->getDateHelper()->formattedDate($date);
    }
}
