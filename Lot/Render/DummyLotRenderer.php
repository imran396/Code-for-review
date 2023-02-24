<?php
/**
 * Dummy service for stubbing LotRenderer in unit tests.
 *
 * SAM-8543: Dummy classes for service stubbing in unit tests
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
use Sam\Core\AuctionLot\LotNo\Parse\LotNoParsed;
use Sam\Core\Lot\Render\LotPureRenderer;
use Sam\Core\LotItem\ItemNo\Parse\LotItemNoParsed;
use Sam\Core\Service\Dummy\DummyServiceTrait;

/**
 * Class LotRenderer
 * @package Sam\Lot\Render
 */
class DummyLotRenderer implements LotRendererInterface
{
    use DummyServiceTrait;

    // --- Lot Name ---

    /**
     * {@inheritDoc}
     */
    public function makeName(string $lotName, bool $isTestAuction = false, array $optionals = []): string
    {
        return LotPureRenderer::new()->makeName($lotName, $isTestAuction, 'test:');
    }

    // --- Lot Status ---

    /**
     * {@inheritDoc}
     */
    public function makeLotStatusTranslated(
        int $lotStatus,
        bool $isReverseAuction = false,
        bool $isSoldWithReservation = false,
        ?int $systemAccountId = null,
        ?int $languageId = null
    ): string {
        return $this->toString(func_get_args());
    }

    // --- Lot No ---

    /**
     * {@inheritDoc}
     */
    public function makeLotNo(int|string|null $lotNum, ?string $lotNumExt, ?string $lotNumPrefix, array $optionals = []): string
    {
        return LotPureRenderer::new()->makeLotNo((string)$lotNum, (string)$lotNumExt, (string)$lotNumPrefix, '~', '~');
    }

    /**
     * {@inheritDoc}
     */
    public function makeLotNoByParsed(LotNoParsed $lotNoParsed, array $optionals = []): string
    {
        return LotPureRenderer::new()->makeLotNoByParsed($lotNoParsed, '~', '~');
    }

    /**
     * {@inheritDoc}
     */
    public function renderLotNo(?AuctionLotItem $auctionLot, array $optionals = []): string
    {
        return LotPureRenderer::new()->makeLotNoByEntity($auctionLot, '~', '~');
    }

    // --- Item No ---

    /**
     * {@inheritDoc}
     */
    public function makeItemNo(int|string|null $itemNum, ?string $itemNumExt, array $optionals = []): string
    {
        return LotPureRenderer::new()->makeItemNo((string)$itemNum, (string)$itemNumExt, '~');
    }

    /**
     * {@inheritDoc}
     */
    public function makeItemNoByParsed(LotItemNoParsed $itemNoParsed, array $optionals = []): string
    {
        return LotPureRenderer::new()->makeItemNoByParsed($itemNoParsed, '~');
    }

    /**
     * {@inheritDoc}
     */
    public function renderItemNo(?LotItem $lotItem, array $optionals = []): string
    {
        return LotPureRenderer::new()->makeItemNoByEntity($lotItem, '~');
    }

    // --- Lot No and Item No ---

    /**
     * {@inheritDoc}
     */
    public function makeLotNoWithItemNo(
        ?int $lotNum,
        string $lotNumExt,
        string $lotNumPrefix,
        ?int $itemNum,
        string $itemNumExt,
        array $optionals = []
    ): string {
        return $this->toString(func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function makeLotNoWithItemNoCompact(
        ?int $lotNum,
        string $lotNumExt,
        string $lotNumPrefix,
        ?int $itemNum,
        string $itemNumExt,
        array $optionals = []
    ): string {
        return $this->toString(func_get_args());
    }

    /**
     * {@inheritDoc}
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
        return $this->toString(func_get_args());
    }

    /**
     * {@inheritDoc}
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
        return $this->toString(func_get_args());
    }
}
