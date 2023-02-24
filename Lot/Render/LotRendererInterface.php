<?php
/**
 * SAM-8543: Dummy classes for service stubbing in unit tests
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 08, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Render;

use AuctionLotItem;
use LotItem;
use Sam\Core\AuctionLot\LotNo\Parse\LotNoParsed;
use Sam\Core\LotItem\ItemNo\Parse\LotItemNoParsed;

interface LotRendererInterface
{
    // --- Lot Name ---

    /**
     * Construct lot name with consideration of a.test_auction option for prefix.
     * @param string $lotName
     * @param bool $isTestAuction a.test_auction
     * @param array $optionals
     * @return string
     */
    public function makeName(string $lotName, bool $isTestAuction = false, array $optionals = []): string;

    // --- Lot Status ---

    /**
     * Construct and translate lot status on base of input arguments.
     * @param int $lotStatus
     * @param bool $isReverseAuction
     * @param bool $isSoldWithReservation
     * @param int|null $systemAccountId
     * @param int|null $languageId
     * @return string
     */
    public function makeLotStatusTranslated(
        int $lotStatus,
        bool $isReverseAuction = false,
        bool $isSoldWithReservation = false,
        ?int $systemAccountId = null,
        ?int $languageId = null
    ): string;

    // --- Lot No ---

    /**
     * Assemble full lot# from parts (ali.lot_num_prefix + ali.lot_num + ali.lot_num_ext) and consider both separators.
     * @param int|string|null $lotNum NULL if lot is not assigned to auction. May be a string for some template on "lot num" place, e.g. "<lot num>".
     * @param string|null $lotNumExt NULL if lot is not assigned to auction
     * @param string|null $lotNumPrefix NULL if lot is not assigned to auction
     * @param array $optionals
     * @return string
     */
    public function makeLotNo(int|string|null $lotNum, ?string $lotNumExt, ?string $lotNumPrefix, array $optionals = []): string;

    /**
     * Assemble full lot# from parts passed in DTO and consider both separators.
     * @param LotNoParsed $lotNoParsed
     * @param array $optionals
     * @return string
     */
    public function makeLotNoByParsed(LotNoParsed $lotNoParsed, array $optionals = []): string;

    /**
     * Render lot# on base of input AuctionLotItem entity.
     * @param AuctionLotItem|null $auctionLot null leads to empty results.
     * @param array $optionals
     * @return string
     */
    public function renderLotNo(?AuctionLotItem $auctionLot, array $optionals = []): string;

    // --- Item No ---

    /**
     * Construct item# on base of input arguments.
     * @param int|string|null $itemNum
     * @param string|null $itemNumExt
     * @param array $optionals
     * @return string
     */
    public function makeItemNo(int|string|null $itemNum, ?string $itemNumExt, array $optionals = []): string;

    /**
     * Construct item# from parts passed in DTO and consider separator.
     * @param LotItemNoParsed $itemNoParsed
     * @param array $optionals
     * @return string
     */
    public function makeItemNoByParsed(LotItemNoParsed $itemNoParsed, array $optionals = []): string;

    /**
     * Render item# on base of input LotItem entity.
     * @param LotItem|null $lotItem
     * @param array $optionals
     * @return string
     */
    public function renderItemNo(?LotItem $lotItem, array $optionals = []): string;

    // --- Lot No and Item No ---

    /**
     * Make non-translated "Lot# & Item#" for complete view.
     * @param int|null $lotNum
     * @param string $lotNumExt
     * @param string $lotNumPrefix
     * @param int|null $itemNum
     * @param string $itemNumExt
     * @param array $optionals
     * @return string
     */
    public function makeLotNoWithItemNo(
        ?int $lotNum,
        string $lotNumExt,
        string $lotNumPrefix,
        ?int $itemNum,
        string $itemNumExt,
        array $optionals = []
    ): string;

    /**
     * Make non-translated "Lot# & Item#" for compact view.
     * @param int|null $lotNum
     * @param string $lotNumExt
     * @param string $lotNumPrefix
     * @param int|null $itemNum
     * @param string $itemNumExt
     * @param array $optionals
     * @return string
     */
    public function makeLotNoWithItemNoCompact(
        ?int $lotNum,
        string $lotNumExt,
        string $lotNumPrefix,
        ?int $itemNum,
        string $itemNumExt,
        array $optionals = []
    ): string;

    /**
     * Make translated "Lot# and Item#" for complete view.
     * @param int|null $lotNum
     * @param string $lotNumExt
     * @param string $lotNumPrefix
     * @param int|null $itemNum
     * @param string $itemNumExt
     * @param int|null $translatorAccountId
     * @param int|null $languageId
     * @param array $optionals
     * @return string
     */
    public function makeLotNoWithItemNoTranslated(
        ?int $lotNum,
        string $lotNumExt,
        string $lotNumPrefix,
        ?int $itemNum,
        string $itemNumExt,
        ?int $translatorAccountId,
        ?int $languageId,
        array $optionals = []
    ): string;

    /**
     * Make translated "Lot# & Item#" for compact view.
     * @param int|null $lotNum
     * @param string $lotNumExt
     * @param string $lotNumPrefix
     * @param int|null $itemNum
     * @param string $itemNumExt
     * @param int|null $translatorAccountId
     * @param int|null $languageId
     * @param array $optionals
     * @return string
     */
    public function makeLotNoWithItemNoCompactTranslated(
        ?int $lotNum,
        string $lotNumExt,
        string $lotNumPrefix,
        ?int $itemNum,
        string $itemNumExt,
        ?int $translatorAccountId,
        ?int $languageId,
        array $optionals = []
    ): string;
}
