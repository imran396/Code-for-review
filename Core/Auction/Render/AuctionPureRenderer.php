<?php
/**
 * SAM-4105: Auction fields renderer
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 21, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Auction\Render;

use Auction;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Core\Transform\Text\NewLineRemover;

/**
 * Class AuctionPureRenderer
 * @package Sam\Core\Auction\Render
 */
class AuctionPureRenderer extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $auctionName
     * @param bool $isTestAuction
     * @param string $prefix
     * @param array $allowedTags
     * @return string
     */
    public function makeName(
        string $auctionName,
        bool $isTestAuction = false,
        string $prefix = '',
        array $allowedTags = []
    ): string {
        $output = NewLineRemover::new()->replaceWithSpace($auctionName);
        $output = $allowedTags
            ? strip_tags($output, implode('', $allowedTags))
            : strip_tags($output);
        $output = trim($output);
        if ($output === '') {
            return '';
        }
        $prefix = $isTestAuction ? $prefix : '';
        $output = $prefix . $output;
        return $output;
    }

    /**
     * Render auction name considering a.test_auction option
     * @param Auction|null $auction
     * @param string $prefix
     * @param array $allowedTags
     * @return string
     */
    public function makeNameByEntity(?Auction $auction, string $prefix = '', array $allowedTags = []): string
    {
        return $auction
            ? $this->makeName($auction->Name, $auction->TestAuction, $prefix, $allowedTags)
            : '';
    }

    /**
     * Renders sale number + extension (with separator)
     * @param string $saleNo
     * @param string $saleNoExt
     * @param string $extensionSeparator
     * @return string
     */
    public function makeSaleNo(string $saleNo, string $saleNoExt, string $extensionSeparator): string
    {
        $separator = $saleNo !== '' && $saleNoExt !== '' ? $extensionSeparator : '';
        return $saleNo . $separator . $saleNoExt;
    }

    /**
     * @param Auction|null $auction
     * @param string $extensionSeparator
     * @return string
     */
    public function makeSaleNoByEntity(?Auction $auction, string $extensionSeparator): string
    {
        return $auction
            ? $this->makeSaleNo(
                (string)$auction->SaleNum,
                $auction->SaleNumExt,
                $extensionSeparator
            )
            : '';
    }

    /**
     * @param string|null $auctionType null leads to empty string
     * @return string
     */
    public function makeAuctionType(?string $auctionType): string
    {
        return Constants\Auction::$auctionTypeNames[$auctionType] ?? '';
    }

    /**
     * @param int $auctionStatus
     * @return string
     */
    public function makeAuctionStatus(int $auctionStatus): string
    {
        return Constants\Auction::$auctionStatusNames[$auctionStatus] ?? '';
    }

    /**
     * @param int|null $strategy
     * @return string
     */
    public function makeDateAssignmentStrategy(?int $strategy): string
    {
        return Constants\Auction::$dateAssignmentStrategies[$strategy] ?? '';
    }

    /**
     * Return name of lot ordering option
     *
     * @param int $orderType default lot order type
     * @param string|null $customFieldName lot item custom field name, if order type is Constants\Auction::LOT_ORDER_BY_CUST_FIELD
     * @return string
     */
    public function makeOrderOptionName(int $orderType, string $customFieldName = null): string
    {
        return match ($orderType) {
            Constants\Auction::LOT_ORDER_BY_LOT_NUMBER => 'Lot#',
            Constants\Auction::LOT_ORDER_BY_ITEM_NUMBER => 'Item#',
            Constants\Auction::LOT_ORDER_BY_CUST_FIELD => $customFieldName ?: 'Custom field',
            Constants\Auction::LOT_ORDER_BY_CATEGORY => 'Category',
            Constants\Auction::LOT_ORDER_BY_NAME => 'Name',
            default => '',
        };
    }
}
