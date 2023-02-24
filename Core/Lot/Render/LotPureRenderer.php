<?php
/**
 * Pure deterministic lot rendering logic.
 *
 * SAM-8682: Adjust Lot Renderer service with strict types and optional parameters
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 19, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Lot\Render;

use AuctionLotItem;
use LotItem;
use Sam\Core\AuctionLot\LotNo\Parse\LotNoParsed;
use Sam\Core\LotItem\ItemNo\Parse\LotItemNoParsed;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Core\Transform\Text\NewLineRemover;

/**
 * Class PureLotRenderer
 * @package Sam\Core\Lot\Render
 */
class LotPureRenderer extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    // --- Lot Name ---

    /**
     * Render lot name with prefix.
     * Prefix is not sanitized, but prepended as is.
     * Filter NewLine sequences of any input (web, soap, csv). UTF-8 support.
     * @param string $lotName
     * @param bool $isTestAuction
     * @param string $prefix
     * @return string
     */
    public function makeName(string $lotName, bool $isTestAuction = false, string $prefix = ''): string
    {
        $output = NewLineRemover::new()->replaceWithSpace($lotName);
        $output = trim($output);
        if ($output === '') {
            return '';
        }
        $prefix = $isTestAuction ? $prefix : '';
        $output = $prefix . $output;
        return $output;
    }

    // --- Lot Status ---

    /**
     * Construct lot status on base of input arguments.
     * @param int $lotStatus
     * @param bool $isReverseAuction
     * @param bool $isSoldWithReservation
     * @return string
     */
    public function makeLotStatus(
        int $lotStatus,
        bool $isReverseAuction = false,
        bool $isSoldWithReservation = false
    ): string {
        if ($isReverseAuction) {
            $langStatuses = Constants\Lot::$lotStatusNamesForReverseAuction;
        } else {
            $langStatuses = Constants\Lot::$lotStatusNames;
            // Conditional sale is available for "Forward" auctions only
            if ($isSoldWithReservation) {
                $langStatuses[Constants\Lot::LS_SOLD] = Constants\Lot::SOLD_WITH_RESERVATION_NAME;
            }
        }
        $output = $langStatuses[$lotStatus];
        return $output;
    }

    // --- Lot No ---

    /**
     * Assemble full lot# from parts (ali.lot_num_prefix + ali.lot_num + ali.lot_num_ext) and consider both separators
     * @param string $lotNum
     * @param string $lotNumExt
     * @param string $lotNumPrefix
     * @param string $prefixSeparator
     * @param string $extensionSeparator
     * @return string
     */
    public function makeLotNo(
        string $lotNum,
        string $lotNumExt,
        string $lotNumPrefix,
        string $extensionSeparator,
        string $prefixSeparator
    ): string {
        $prefixWithSeparator = $lotNumPrefix !== '' ? $lotNumPrefix . $prefixSeparator : '';
        $extensionWithSeparator = $lotNumExt !== '' ? $extensionSeparator . $lotNumExt : '';
        $lotNo = trim($prefixWithSeparator . $lotNum . $extensionWithSeparator);
        return $lotNo;
    }

    /**
     * Assemble full lot# from parts passed in DTO and consider both separators
     * @param LotNoParsed $lotNoParsed
     * @param string $prefixSeparator
     * @param string $extensionSeparator
     * @return string
     */
    public function makeLotNoByParsed(
        LotNoParsed $lotNoParsed,
        string $extensionSeparator,
        string $prefixSeparator
    ): string {
        return $this->makeLotNo(
            (string)$lotNoParsed->lotNum,
            $lotNoParsed->lotNumExtension,
            $lotNoParsed->lotNumPrefix,
            $extensionSeparator,
            $prefixSeparator
        );
    }

    /**
     * Render lot# on base of input AuctionLotItem entity.
     * @param AuctionLotItem|null $auctionLot null leads to empty results.
     * @param string $extensionSeparator
     * @param string $prefixSeparator
     * @return string
     */
    public function makeLotNoByEntity(
        ?AuctionLotItem $auctionLot,
        string $extensionSeparator,
        string $prefixSeparator
    ): string {
        if (!$auctionLot) {
            return '';
        }
        return $this->makeLotNo(
            (string)$auctionLot->LotNum,
            $auctionLot->LotNumExt,
            $auctionLot->LotNumPrefix,
            $extensionSeparator,
            $prefixSeparator
        );
    }

    /**
     * Render lot# without separators on base of input AuctionLotItem entity.
     * @param AuctionLotItem|null $auctionLot
     * @return string
     */
    public function makeLotNoWithoutSeparatorsByEntity(?AuctionLotItem $auctionLot): string
    {
        return $this->makeLotNoByEntity($auctionLot, '', '');
    }

    // --- Item No ---

    /**
     * Construct item# on base of input arguments.
     * @param string $itemNum
     * @param string $itemNumExt
     * @param string $extensionSeparator
     * @return string
     */
    public function makeItemNo(
        string $itemNum,
        string $itemNumExt,
        string $extensionSeparator
    ): string {
        $extensionWithSeparator = $itemNumExt !== '' ? $extensionSeparator . $itemNumExt : '';
        $itemNo = trim($itemNum . $extensionWithSeparator);
        return $itemNo;
    }

    /**
     * Construct item# from parts passed in DTO and consider both separators
     * @param LotItemNoParsed $itemNoParsed
     * @param string $extensionSeparator
     * @return string
     */
    public function makeItemNoByParsed(LotItemNoParsed $itemNoParsed, string $extensionSeparator): string
    {
        return $this->makeItemNo((string)$itemNoParsed->itemNum, $itemNoParsed->itemNumExtension, $extensionSeparator);
    }

    /**
     * Render item# on base of input LotItem entity.
     * @param LotItem|null $lotItem
     * @param string $extensionSeparator
     * @return string
     */
    public function makeItemNoByEntity(
        ?LotItem $lotItem,
        string $extensionSeparator
    ): string {
        if (!$lotItem) {
            return '';
        }
        return $this->makeItemNo(
            (string)$lotItem->ItemNum,
            $lotItem->ItemNumExt,
            $extensionSeparator
        );
    }
}
