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

namespace Sam\Application\Controller\Responsive\Common\Autocomplete\Concrete\Auction;

use Sam\Account\Filter\AccountApplicationFilterDetectorAwareTrait;
use Sam\Application\Controller\Admin\Common\Autocomplete\Shared\Account\FilterAccountDetectorCreateTrait;
use Sam\Application\Controller\Responsive\Common\Autocomplete\Concrete\Auction\Internal\Build\AuctionAutocompleteDataBuilderCreateTrait;
use Sam\Application\Controller\Responsive\Common\Autocomplete\Concrete\Auction\Internal\Build\AuctionAutocompleteDataBuildingInput;
use Sam\Application\RequestParam\ParamFetcherForGetAwareTrait;
use Sam\Application\RequestParam\Route\ParamFetcherForRouteAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class AuctionAutocompleteResponseProducer
 * @package Sam\Application\Controller\Responsive\Common\Autocomplete\Concrete\Auction
 */
class AuctionAutocompleteResponseProducer extends CustomizableClass
{
    use AccountApplicationFilterDetectorAwareTrait;
    use AuctionAutocompleteDataBuilderCreateTrait;
    use ConfigRepositoryAwareTrait;
    use EditorUserAwareTrait;
    use FilterAccountDetectorCreateTrait;
    use ParamFetcherForGetAwareTrait;
    use ParamFetcherForRouteAwareTrait;
    use SystemAccountAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return array
     */
    public function produce(): array
    {
        $paramFetcherForGet = $this->getParamFetcherForGet();

        $searchKey = $paramFetcherForGet->has(Constants\UrlParam::SEARCH_KEY)
            ? $paramFetcherForGet->getString(Constants\UrlParam::SEARCH_KEY)
            : '';

        $auctionId = $paramFetcherForGet->has(Constants\UrlParam::AUCTION_ID)
            ? $paramFetcherForGet->getIntPositive(Constants\UrlParam::AUCTION_ID)
            : null;

        $pageType = $paramFetcherForGet->has(Constants\UrlParam::PAGE_TYPE)
            ? $paramFetcherForGet->getString(Constants\UrlParam::PAGE_TYPE)
            : '';

        $auctionTypes = $paramFetcherForGet->getArrayOfKnownSet(
            Constants\UrlParam::AUCTION_TYPE,
            array_merge(Constants\Auction::AUCTION_TYPES, [''])
        );
        $auctionTypes = array_filter($auctionTypes);

        $filterAccountId = $this->createFilterAccountDetector()
            ->detectFilterAccountId($this->getEditorUserId(), $this->getSystemAccountId());

        $limit = $this->cfg()->get('core->search->filter->auctionAutocomplete->limit');

        $input = AuctionAutocompleteDataBuildingInput::new()->construct(
            $searchKey,
            $auctionId,
            $pageType,
            $auctionTypes,
            $limit,
            $this->getEditorUserId(),
            $filterAccountId,
            $this->getSystemAccountId()
        );
        $data = $this->createAuctionAutocompleteDataBuilder()->build($input);
        return $data;
    }
}
