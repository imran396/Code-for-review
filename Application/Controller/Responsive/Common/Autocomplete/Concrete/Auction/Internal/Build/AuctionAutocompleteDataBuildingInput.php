<?php
/**
 * SAM-5466: Advanced search panel auction auto-complete configuration
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 12, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Common\Autocomplete\Concrete\Auction\Internal\Build;

use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionAutocompleteDataBuildingInput
 * @package Sam\Application\Controller\Responsive\Common\Autocomplete\Concrete\Auction\Internal\Build
 */
class AuctionAutocompleteDataBuildingInput extends CustomizableClass
{
    /**
     * Empty string means absent search
     */
    public string $searchKey = '';
    public ?int $auctionId;
    public string $pageType;
    public array $auctionTypes;
    public int $limit;
    public ?int $editorUserId;
    public ?int $filterAccountId;
    public int $systemAccountId;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $searchKey
     * @param int|null $auctionId
     * @param string $pageType
     * @param array $auctionTypes
     * @param int $limit
     * @param int|null $editorUserId
     * @param int|null $filterAccountId
     * @param int $systemAccountId
     * @return $this
     */
    public function construct(
        string $searchKey,
        ?int $auctionId,
        string $pageType,
        array $auctionTypes,
        int $limit,
        ?int $editorUserId,
        ?int $filterAccountId,
        int $systemAccountId
    ): static {
        $this->searchKey = $searchKey;
        $this->auctionId = $auctionId;
        $this->pageType = $pageType;
        $this->auctionTypes = $auctionTypes;
        $this->limit = $limit;
        $this->editorUserId = $editorUserId;
        $this->filterAccountId = $filterAccountId;
        $this->systemAccountId = $systemAccountId;
        return $this;
    }
}
