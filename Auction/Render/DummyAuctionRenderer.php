<?php
/**
 * Dummy service for stubbing AuctionRenderer in unit tests.
 *
 * SAM-8543: Dummy classes for service stubbing in unit tests
 * SAM-4105: Auction fields renderer
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

namespace Sam\Auction\Render;

use Auction;
use Sam\Core\Auction\Render\AuctionPureRenderer;
use Sam\Core\Service\Dummy\DummyServiceTrait;

/**
 * Class DummyAuctionRenderer
 * @package Sam\Auction\Render
 */
class DummyAuctionRenderer implements AuctionRendererInterface
{
    use DummyServiceTrait;

    /**
     * {@inheritDoc}
     */
    public function makeName(?string $auctionName, bool $isTestAuction = false, bool $isHtml = false, array $optionals = []): string
    {
        return AuctionPureRenderer::new()->makeName((string)$auctionName, $isTestAuction, 'test:', ['<b>', '<i>']);
    }

    /**
     * {@inheritDoc}
     */
    public function renderName(?Auction $auction, bool $isHtml = false, array $optionals = []): string
    {
        return AuctionPureRenderer::new()->makeNameByEntity($auction, 'test:', ['<b>', '<i>']);
    }

    /**
     * {@inheritDoc}
     */
    public function makeSaleNo($saleNo, ?string $saleNoExt, array $optionals = []): string
    {
        return AuctionPureRenderer::new()->makeSaleNo((string)$saleNo, (string)$saleNoExt, '~');
    }

    /**
     * {@inheritDoc}
     */
    public function renderSaleNo(?Auction $auction, array $optionals = []): string
    {
        return AuctionPureRenderer::new()->makeSaleNoByEntity($auction, '~');
    }

    /**
     * {@inheritDoc}
     */
    public function makeAuctionStatusTranslated(int $auctionStatus, ?int $accountId = null, ?int $languageId = null): string
    {
        return $this->toString(func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function makeGeneralStatus(
        int $auctionStatus,
        string $auctionType,
        ?int $eventType,
        string $startDateUtcIso,
        string $endDateUtcIso
    ): string {
        return $this->toString(func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function makeGeneralStatusTranslated(
        int $auctionStatus,
        string $auctionType,
        ?int $eventType,
        string $startDateUtcIso,
        string $endDateUtcIso,
        ?int $accountId = null,
        ?int $languageId = null
    ): string {
        return $this->toString(func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function makeAuctionTypeTranslated(?string $auctionType, ?int $accountId = null, ?int $languageId = null): string
    {
        return $this->toString(func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function renderImageTag(Auction $auction, string $size): string
    {
        return $this->toString(func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function makeImageTag(int $auctionImageId, ?string $size, ?int $accountId = null): string
    {
        return $this->toString(func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function makeImageUrl(int $auctionImageId, ?string $size = null, ?int $accountId = null): string
    {
        return $this->toString(func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function renderDates(Auction $auction): ?string
    {
        return $this->toString(func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function renderDatesTranslated(Auction $auction, ?int $accountId = null, ?int $languageId = null): ?string
    {
        return $this->toString(func_get_args());
    }
}
