<?php
/**
 * SAM-10097: Distinguish auction bidder autocomplete data loading end-points for different pages
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\AuctionBidder\Internal\Build;

use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionBidderAutocompleteDataBuildingInput
 * @package Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\AuctionBidder\Internal\Build
 */
class AuctionBidderAutocompleteDataBuildingInput extends CustomizableClass
{
    /**
     * @var string empty string means absent search
     */
    public string $searchKeyword = '';
    /**
     * @var int|null lot item influence to filtering results
     */
    public ?int $contextLotItemId;
    /**
     * @var int|null auction influence to filtering results
     */
    public ?int $filterAuctionId;
    /**
     * @var int|null
     */
    public ?int $filterAccountId;
    /**
     * @var int|null null means unlimited
     */
    public ?int $limit;
    /**
     * @var int|null defines auction context where search is performed.
     */
    public ?int $contextAuctionId;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $searchKeyword
     * @param int|null $contextLotItemId
     * @param int|null $filterAuctionId
     * @param int|null $contextAuctionId
     * @param int|null $filterAccountId
     * @param int|null $limit
     * @return $this
     */
    public function construct(
        string $searchKeyword,
        ?int $contextLotItemId,
        ?int $filterAuctionId,
        ?int $contextAuctionId,
        ?int $filterAccountId,
        ?int $limit
    ): static {
        $this->searchKeyword = $searchKeyword;
        $this->contextLotItemId = $contextLotItemId;
        $this->filterAuctionId = $filterAuctionId;
        $this->contextAuctionId = $contextAuctionId;
        $this->filterAccountId = $filterAccountId;
        $this->limit = $limit;
        return $this;
    }

}
