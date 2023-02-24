<?php
/**
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

interface AuctionRendererInterface
{
    /**
     * Render auction name, strip tags for admin pages, render unescaped for the public front pages
     * @param string|null $auctionName null when we render name of absent record
     * @param bool $isTestAuction
     * @param bool $isHtml
     * @param array $optionals
     * @return string
     */
    public function makeName(?string $auctionName, bool $isTestAuction = false, bool $isHtml = false, array $optionals = []): string;

    /**
     * Render auction name considering a.test_auction option
     * @param Auction|null $auction
     * @param bool $isHtml
     * @param array $optionals
     * @return string
     */
    public function renderName(?Auction $auction, bool $isHtml = false, array $optionals = []): string;

    /**
     * Renders sale number + extension (with separator)
     * @param int|string|null $saleNo null means rendering for absent auction
     * @param string|null $saleNoExt null means rendering for absent auction
     * @param array $optionals
     * @return string
     */
    public function makeSaleNo(int|string|null $saleNo, ?string $saleNoExt, array $optionals = []): string;

    /**
     * Render sale number with extension
     * @param Auction|null $auction
     * @param array $optionals
     * @return string
     */
    public function renderSaleNo(?Auction $auction, array $optionals = []): string;

    /**
     * @param int $auctionStatus
     * @param int|null $accountId
     * @param int|null $languageId
     * @return string
     */
    public function makeAuctionStatusTranslated(int $auctionStatus, ?int $accountId = null, ?int $languageId = null): string;

    /**
     * General auction status (In progress, Upcoming, Closed)
     * @param int $auctionStatus
     * @param string $auctionType
     * @param int|null $eventType
     * @param string $startDateUtcIso
     * @param string $endDateUtcIso
     * @return string
     */
    public function makeGeneralStatus(
        int $auctionStatus,
        string $auctionType,
        ?int $eventType,
        string $startDateUtcIso,
        string $endDateUtcIso
    ): string;

    /**
     * @param int $auctionStatus
     * @param string $auctionType
     * @param int|null $eventType
     * @param string $startDateUtcIso
     * @param string $endDateUtcIso
     * @param int|null $accountId
     * @param int|null $languageId
     * @return string
     */
    public function makeGeneralStatusTranslated(
        int $auctionStatus,
        string $auctionType,
        ?int $eventType,
        string $startDateUtcIso,
        string $endDateUtcIso,
        ?int $accountId = null,
        ?int $languageId = null
    ): string;

    /**
     * @param string|null $auctionType
     * @param int|null $accountId
     * @param int|null $languageId
     * @return string
     */
    public function makeAuctionTypeTranslated(?string $auctionType, ?int $accountId = null, ?int $languageId = null): string;

    /**
     * @param Auction $auction
     * @param string $size
     * @return string
     */
    public function renderImageTag(Auction $auction, string $size): string;

    /**
     * Return <img> tag for auction image
     * @param int $auctionImageId
     * @param string|null $size
     * @param int|null $accountId null for main account
     * @return string
     */
    public function makeImageTag(int $auctionImageId, ?string $size, ?int $accountId = null): string;

    /**
     * Return url for auction image
     * @param int $auctionImageId
     * @param string|null $size
     * @param int|null $accountId null for main account
     * @return string
     */
    public function makeImageUrl(int $auctionImageId, ?string $size = null, ?int $accountId = null): string;

    /**
     * @param Auction $auction
     * @return string
     */
    public function renderDates(Auction $auction): ?string;

    /**
     * @param Auction $auction
     * @param int|null $accountId
     * @param int|null $languageId
     * @return string
     */
    public function renderDatesTranslated(Auction $auction, ?int $accountId = null, ?int $languageId = null): ?string;
}
