<?php
/**
 * SAM-8543: Dummy classes for service stubbing in unit tests
 * SAM-4449: Language label translation modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           9/17/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lang;

use Auction;

interface TranslatorInterface
{
    public function construct(int $serviceAccountId, int $languageId): self;

    public function setAccountId(?int $accountId): self;

    public function setLanguageId(?int $languageId): self;

    /**
     * @param string $fieldKey
     * @param string $section
     * @param int|null $accountId
     * @param int|null $languageId
     * @param bool $isCached
     * @return string
     */
    public function translate(
        string $fieldKey,
        string $section = 'mainmenu',
        ?int $accountId = null,
        ?int $languageId = null,
        bool $isCached = true
    ): string;

    /**
     * Translations for RTB(Live, Hybrid)
     *
     * @param string $label
     * @param Auction $auction
     * @param int|null $accountId
     * @param int|null $viewLanguageId
     * @return string
     */
    public function translateForRtb(
        string $label,
        Auction $auction,
        ?int $accountId = null,
        ?int $viewLanguageId = null
    ): string;

    /**
     * Translations for RTB(Live, Hybrid)
     * @param string $label
     * @param string $auctionType
     * @param int|null $accountId
     * @param int|null $viewLanguageId
     * @return string
     */
    public function translateForRtbByAuctionType(
        string $label,
        string $auctionType,
        ?int $accountId = null,
        ?int $viewLanguageId = null
    ): string;

    /**
     * @param string $fieldKey
     * @param string $section
     * @param bool $isReverse
     * @param int|null $accountId
     * @param int|null $languageId
     * @param bool $isCached
     * @return string
     */
    public function translateByAuctionReverse(
        string $fieldKey,
        string $section,
        bool $isReverse = false,
        ?int $accountId = null,
        ?int $languageId = null,
        bool $isCached = true
    ): string;
}
