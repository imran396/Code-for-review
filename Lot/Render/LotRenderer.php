<?php
/**
 * Helping methods for lot fields rendering.
 * It doesn't render numeric fields that require specific to account formatting. They are rendered by LotAmountRenderer service.
 *
 * SAM-8682: Adjust Lot Renderer service with strict types and optional parameters
 * SAM-4116: Lot Renderer class
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 23, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Render;

use AuctionLotItem;
use LotItem;
use Sam\AuctionLot\Quantity\Scale\LotQuantityScaleLoaderCreateTrait;
use Sam\Core\AuctionLot\LotNo\Parse\LotNoParsed;
use Sam\Core\Constants;
use Sam\Core\Lot\Render\LotPureRenderer;
use Sam\Core\LotItem\ItemNo\Parse\LotItemNoParsed;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class LotRenderer
 * @package Sam\Lot\Render
 */
class LotRenderer extends CustomizableClass implements LotRendererInterface
{
    use ConfigRepositoryAwareTrait;
    use LotQuantityScaleLoaderCreateTrait;
    use SettingsManagerAwareTrait;
    use TranslatorAwareTrait;

    public const OP_LOT_NO_EXTENSION_SEPARATOR = OptionalKeyConstants::KEY_LOT_NO_EXTENSION_SEPARATOR; // string
    public const OP_LOT_NO_PREFIX_SEPARATOR = OptionalKeyConstants::KEY_LOT_NO_PREFIX_SEPARATOR; // string
    public const OP_TEST_AUCTION_PREFIX = OptionalKeyConstants::KEY_TEST_AUCTION_PREFIX; // string
    public const OP_ITEM_NO_EXTENSION_SEPARATOR = OptionalKeyConstants::KEY_ITEM_NO_EXTENSION_SEPARATOR; // string
    public const OP_DISPLAY_ITEM_NUM = OptionalKeyConstants::KEY_DISPLAY_ITEM_NUM; // bool

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    // --- Lot Name ---

    /**
     * Render lot name with consideration of a.test_auction option for prefix.
     * Prefix is not sanitized, but prepended as is.
     * @param string $lotName
     * @param bool $isTestAuction a.test_auction
     * @param array $optionals = [
     *     self::OP_AUCTION_TEST_PREFIX => string
     * ]
     * @return string
     */
    public function makeName(
        string $lotName,
        bool $isTestAuction = false,
        array $optionals = []
    ): string {
        $prefix = $isTestAuction  // avoid redundant cfg() reading
            ? $this->fetchTestAuctionPrefix($optionals)
            : '';
        return LotPureRenderer::new()->makeName($lotName, $isTestAuction, $prefix);
    }

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
    ): string {
        $langStatuses = [
            Constants\Lot::LS_UNASSIGNED => ['LD_STATUS_UNASSIGNED', 'lot_details'],
            Constants\Lot::LS_ACTIVE => ['LD_STATUS_ACTIVE', 'lot_details'],
            Constants\Lot::LS_UNSOLD => ['LD_STATUS_UNSOLD', 'lot_details'],
            Constants\Lot::LS_SOLD => ['LD_STATUS_SOLD', 'lot_details'],
            Constants\Lot::LS_DELETED => ['LD_STATUS_DELETED', 'lot_details'],
            Constants\Lot::LS_RECEIVED => ['LD_STATUS_RECEIVED', 'lot_details'],
        ];
        if ($isReverseAuction) {
            $langStatuses[Constants\Lot::LS_UNSOLD] = ['LD_STATUS_UNAWARDED', 'lot_details'];
            $langStatuses[Constants\Lot::LS_SOLD] = ['LD_STATUS_AWARDED', 'lot_details'];
        } else {
            // Conditional sale is available for "Forward" auctions only
            if ($isSoldWithReservation) {
                $langStatuses[Constants\Lot::LS_SOLD] = ['LD_STATUS_SOLD_CONDITIONAL', 'lot_details'];
            }
        }
        $output = $this->getTranslator()->translate(
            $langStatuses[$lotStatus][0],
            $langStatuses[$lotStatus][1],
            $systemAccountId,
            $languageId
        );
        return $output;
    }

    // --- Lot No ---

    /**
     * Assemble full lot# from parts (ali.lot_num_prefix + ali.lot_num + ali.lot_num_ext) and consider both separators
     * @param int|string|null $lotNum Union type is chosen, because it is higher level method that is intended for various usages.
     *      Int - is the base DB type of ali.lot_num;
     *      String - we may want to pass some placeholder (eg. "<lot num>") for lot# template rendering;
     *      Null - expected value, when lot number is not defined;
     *           - or when lot item is not assigned to auction, i.e. we don't have data for lot# construction;
     * @param string|null $lotNumExt
     *      String - is the base DB type of ali.lot_num_ext;
     *      Null - when lot item is not assigned to auction, i.e. we don't have data for lot# construction;
     * @param string|null $lotNumPrefix
     *      String - is the base DB type of ali.lot_num_prefix;
     *      Null - when lot item is not assigned to auction, i.e. we don't have data for lot# construction;
     * @param array $optionals = [
     *      self::OP_LOT_NO_PREFIX_SEPARATOR => string,
     *      self::OP_LOT_NO_EXTENSION_SEPARATOR => string,
     * ]
     * @return string
     */
    public function makeLotNo(
        int|string|null $lotNum,
        ?string $lotNumExt,
        ?string $lotNumPrefix,
        array $optionals = []
    ): string {
        return LotPureRenderer::new()->makeLotNo(
            (string)$lotNum,
            (string)$lotNumExt,
            (string)$lotNumPrefix,
            $this->fetchLotNoExtensionSeparator($optionals),
            $this->fetchLotNoPrefixSeparator($optionals)
        );
    }

    /**
     * Assemble full lot# from parts passed in DTO and consider both separators.
     * @param LotNoParsed $lotNoParsed
     * @param array $optionals = [
     *     self::OP_LOT_NO_PREFIX_SEPARATOR => string,
     *     self::OP_LOT_NO_EXTENSION_SEPARATOR => string,
     * ]
     * @return string
     */
    public function makeLotNoByParsed(LotNoParsed $lotNoParsed, array $optionals = []): string
    {
        return LotPureRenderer::new()->makeLotNoByParsed(
            $lotNoParsed,
            $this->fetchLotNoExtensionSeparator($optionals),
            $this->fetchLotNoPrefixSeparator($optionals)
        );
    }

    /**
     * Render lot# on base of input AuctionLotItem entity.
     * @param AuctionLotItem|null $auctionLot null leads to empty results.
     * @param array $optionals = [
     *     self::OP_LOT_NO_PREFIX_SEPARATOR => string,
     *     self::OP_LOT_NO_EXTENSION_SEPARATOR => string,
     * ]
     * @return string
     */
    public function renderLotNo(?AuctionLotItem $auctionLot, array $optionals = []): string
    {
        return LotPureRenderer::new()->makeLotNoByEntity(
            $auctionLot,
            $this->fetchLotNoExtensionSeparator($optionals),
            $this->fetchLotNoPrefixSeparator($optionals)
        );
    }

    // --- Item No ---

    /**
     * Construct item# on base of input arguments.
     * @param int|string|null $itemNum Union type is chosen, because it is higher level method that is intended for various usages.
     *      Int - is the base DB type of li.item_num;
     *      String - we may want to pass some placeholder (eg. "<item num>") for item# template rendering;
     *      Null - when we don't have data for item# construction;
     * @param string|null $itemNumExt
     *      String - is the base DB type of li.item_num_ext;
     *      Null - when we don't have data for item# construction;
     * @param array $optionals = [
     *      self::OP_ITEM_NO_EXTENSION_SEPARATOR => string
     * ]
     * @return string
     */
    public function makeItemNo(int|string|null $itemNum, ?string $itemNumExt, array $optionals = []): string
    {
        return LotPureRenderer::new()->makeItemNo(
            (string)$itemNum,
            (string)$itemNumExt,
            $this->fetchItemNoExtensionSeparator($optionals)
        );
    }

    /**
     * Construct item# on base of parts passed in DTO and extension separator
     * @param LotItemNoParsed $itemNoParsed
     * @param array $optionals = [
     *      self::OP_ITEM_NO_EXTENSION_SEPARATOR => string
     * ]
     * @return string
     */
    public function makeItemNoByParsed(LotItemNoParsed $itemNoParsed, array $optionals = []): string
    {
        return LotPureRenderer::new()->makeItemNoByParsed(
            $itemNoParsed,
            $this->fetchItemNoExtensionSeparator($optionals)
        );
    }

    /**
     * Render item# on base of input LotItem entity.
     * @param LotItem|null $lotItem
     * @param array $optionals = [
     *     self::OP_ITEM_NO_EXTENSION_SEPARATOR => string
     * ]
     * @return string
     */
    public function renderItemNo(?LotItem $lotItem = null, array $optionals = []): string
    {
        return LotPureRenderer::new()->makeItemNoByEntity(
            $lotItem,
            $this->fetchItemNoExtensionSeparator($optionals)
        );
    }

    // --- Lot No and Item No ---

    /**
     * Make non-translated "Lot# & Item#" for complete view.
     * @param int|null $lotNum
     * @param string $lotNumExt
     * @param string $lotNumPrefix
     * @param int|null $itemNum
     * @param string $itemNumExt
     * @param array $optionals = [
     *     self::OP_DISPLAY_ITEM_NUM => bool,
     *     self::OP_ITEM_NO_EXTENSION_SEPARATOR => string
     *     self::OP_LOT_NO_EXTENSION_SEPARATOR => string,
     *     self::OP_LOT_NO_PREFIX_SEPARATOR => string,
     * ]
     * @return string
     */
    public function makeLotNoWithItemNo(
        ?int $lotNum,
        string $lotNumExt,
        string $lotNumPrefix,
        ?int $itemNum,
        string $itemNumExt,
        array $optionals = []
    ): string {
        return $this->internalMakeLotNoWithItemNo(
            $lotNum,
            $lotNumExt,
            $lotNumPrefix,
            $itemNum,
            $itemNumExt,
            false,
            false,
            null,
            null,
            $optionals
        );
    }

    /**
     * Make non-translated "Lot# & Item#" for compact view.
     * @param int|null $lotNum
     * @param string $lotNumExt
     * @param string $lotNumPrefix
     * @param int|null $itemNum
     * @param string $itemNumExt
     * @param array $optionals = [
     *     self::OP_DISPLAY_ITEM_NUM => bool,
     *     self::OP_ITEM_NO_EXTENSION_SEPARATOR => string
     *     self::OP_LOT_NO_EXTENSION_SEPARATOR => string,
     *     self::OP_LOT_NO_PREFIX_SEPARATOR => string,
     * ]
     * @return string
     */
    public function makeLotNoWithItemNoCompact(
        ?int $lotNum,
        string $lotNumExt,
        string $lotNumPrefix,
        ?int $itemNum,
        string $itemNumExt,
        array $optionals = []
    ): string {
        return $this->internalMakeLotNoWithItemNo(
            $lotNum,
            $lotNumExt,
            $lotNumPrefix,
            $itemNum,
            $itemNumExt,
            true,
            false,
            null,
            null,
            $optionals
        );
    }

    /**
     * Make translated "Lot# and Item#" for complete view.
     * @param int|null $lotNum
     * @param string $lotNumExt
     * @param string $lotNumPrefix
     * @param int|null $itemNum
     * @param string $itemNumExt
     * @param int|null $translatorAccountId
     * @param int|null $languageId
     * @param array $optionals = [
     *     self::OP_DISPLAY_ITEM_NUM => bool,
     *     self::OP_ITEM_NO_EXTENSION_SEPARATOR => string
     *     self::OP_LOT_NO_EXTENSION_SEPARATOR => string,
     *     self::OP_LOT_NO_PREFIX_SEPARATOR => string,
     * ]
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
    ): string {
        return $this->internalMakeLotNoWithItemNo(
            $lotNum,
            $lotNumExt,
            $lotNumPrefix,
            $itemNum,
            $itemNumExt,
            false,
            true,
            $translatorAccountId,
            $languageId,
            $optionals
        );
    }

    /**
     * Make translated "Lot# & Item#" for compact view.
     * @param int|null $lotNum
     * @param string $lotNumExt
     * @param string $lotNumPrefix
     * @param int|null $itemNum
     * @param string $itemNumExt
     * @param int|null $translatorAccountId
     * @param int|null $languageId
     * @param array $optionals = [
     *     self::OP_DISPLAY_ITEM_NUM => bool,
     *     self::OP_ITEM_NO_EXTENSION_SEPARATOR => string
     *     self::OP_LOT_NO_EXTENSION_SEPARATOR => string,
     *     self::OP_LOT_NO_PREFIX_SEPARATOR => string,
     * ]
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
    ): string {
        return $this->internalMakeLotNoWithItemNo(
            $lotNum,
            $lotNumExt,
            $lotNumPrefix,
            $itemNum,
            $itemNumExt,
            true,
            true,
            $translatorAccountId,
            $languageId,
            $optionals
        );
    }

    /**
     * @param int|null $lotNum
     * @param string $lotNumExt
     * @param string $lotNumPrefix
     * @param int|null $itemNum
     * @param string $itemNumExt
     * @param bool $isCompact
     * @param bool $isTranslated
     * @param int|null $translatorAccountId
     * @param int|null $languageId
     * @param array $optionals = [
     *     self::OP_DISPLAY_ITEM_NUM => bool,
     *     self::OP_ITEM_NO_EXTENSION_SEPARATOR => string
     *     self::OP_LOT_NO_EXTENSION_SEPARATOR => string,
     *     self::OP_LOT_NO_PREFIX_SEPARATOR => string,
     * ]
     * @return string
     */
    protected function internalMakeLotNoWithItemNo(
        ?int $lotNum,
        string $lotNumExt = '',
        string $lotNumPrefix = '',
        ?int $itemNum = null,
        string $itemNumExt = '',
        bool $isCompact = false,
        bool $isTranslated = false,
        ?int $translatorAccountId = null,
        ?int $languageId = null,
        array $optionals = []
    ): string {
        $isDisplayItemNum = $this->fetchDisplayItemNum($optionals);
        $itemNo = $isDisplayItemNum ? $this->makeItemNo($itemNum, $itemNumExt, $optionals) : '';
        $lotNo = $this->makeLotNo($lotNum, $lotNumExt, $lotNumPrefix, $optionals);
        $tr = $this->getTranslator();

        if (
            $lotNo !== ''
            && $itemNo !== ''
        ) {
            if ($isTranslated) {
                $template = $isCompact
                    ? $tr->translate('LOT_NO_ITEM_NO_COMPACT', 'auctions', $translatorAccountId, $languageId)
                    : $tr->translate('LOT_NO_ITEM_NO', 'auctions', $translatorAccountId, $languageId);
            } else {
                $template = $isCompact ? "%s / %s" : "Lot #%s / Item #%s";
            }
            return sprintf($template, $lotNo, $itemNo);
        }

        if ($lotNo !== '') {
            if ($isTranslated) {
                $template = $isCompact
                    ? $tr->translate('LOT_NO_COMPACT', 'auctions', $translatorAccountId, $languageId)
                    : $tr->translate('LOT_NO', 'auctions', $translatorAccountId, $languageId);
            } else {
                $template = $isCompact ? "%s" : "Lot #%s";
            }
            return sprintf($template, $lotNo);
        }

        if ($itemNo !== '') {
            if ($isTranslated) {
                $template = $isCompact
                    ? $tr->translate('ITEM_NO_COMPACT', 'auctions', $translatorAccountId, $languageId)
                    : $tr->translate('ITEM_NO', 'auctions', $translatorAccountId, $languageId);
            } else {
                $template = $isCompact ? "%s" : "Item #%s";
            }
            return sprintf($template, $itemNo);
        }

        return '';
    }

    // --- Internal ---

    protected function fetchTestAuctionPrefix(array $optionals): string
    {
        return (string)($optionals[self::OP_TEST_AUCTION_PREFIX]
            ?? $this->cfg()->get('core->auction->test->prefix'));
    }

    protected function fetchLotNoExtensionSeparator(array $optionals): string
    {
        return (string)($optionals[self::OP_LOT_NO_EXTENSION_SEPARATOR]
            ?? $this->cfg()->get('core->lot->lotNo->extensionSeparator'));
    }

    protected function fetchLotNoPrefixSeparator(array $optionals): string
    {
        return (string)($optionals[self::OP_LOT_NO_PREFIX_SEPARATOR]
            ?? $this->cfg()->get('core->lot->lotNo->prefixSeparator'));
    }

    protected function fetchItemNoExtensionSeparator(array $optionals): string
    {
        return (string)($optionals[self::OP_ITEM_NO_EXTENSION_SEPARATOR]
            ?? $this->cfg()->get('core->lot->itemNo->extensionSeparator'));
    }

    protected function fetchDisplayItemNum(array $optionals): bool
    {
        return (bool)($optionals[self::OP_DISPLAY_ITEM_NUM]
            ?? $this->getSettingsManager()->getForMain(Constants\Setting::DISPLAY_ITEM_NUM));
    }
}
