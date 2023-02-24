<?php
/**
 * Web application layer service that provides data for auction bidder auto-completer component.
 * Since it is web application layer, it can fetch parameters from web request, authorized user from user session, system account from Application singleton.
 *
 * SAM-10097: Distinguish auction bidder autocomplete data loading end-points for different pages
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 08, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\AuctionBidder;

use RuntimeException;
use Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\AuctionBidder\Internal\Build\AuctionBidderAutocompleteDataBuilderCreateTrait;
use Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\AuctionBidder\Internal\Build\AuctionBidderAutocompleteDataBuildingInput;
use Sam\Application\Controller\Admin\Common\Autocomplete\Shared\Account\FilterAccountDetectorCreateTrait;
use Sam\Application\RequestParam\ParamFetcherForGetAwareTrait;
use Sam\Application\RequestParam\Route\ParamFetcherForRouteAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Web\ControllerAction\ControllerActionCollection;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class AuctionBidderAutocompleteResponseProducer
 * @package Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\AuctionBidder
 */
class AuctionBidderAutocompleteResponseProducer extends CustomizableClass
{
    use AuctionBidderAutocompleteDataBuilderCreateTrait;
    use ConfigRepositoryAwareTrait;
    use EditorUserAwareTrait;
    use FilterAccountDetectorCreateTrait;
    use ParamFetcherForGetAwareTrait;
    use ParamFetcherForRouteAwareTrait;
    use SystemAccountAwareTrait;

    /**
     * Maximal count of result rows in auto-completer response.
     * @var string[][]
     */
    protected const LIMITS = [
        Constants\AdminRoute::C_MANAGE_AUCTIONS => [
            Constants\AdminRoute::AMA_LOT_LIST_QUICK_EDIT_AUCTION_BIDDER_AUTOCOMPLETE => 'core->admin->auction->lots->quickEdit->auctionBidderAutocomplete->limit',
            Constants\AdminRoute::AMA_PHONE_BIDDER_AUCTION_BIDDER_AUTOCOMPLETE => 'core->admin->auction->phoneBidder->auctionBidderAutocomplete->limit',
            Constants\AdminRoute::AMA_EDIT_LOT_AUCTION_BIDDER_AUTOCOMPLETE => 'core->admin->auction->editLot->auctionBidderAutocomplete->limit',
        ],
        Constants\AdminRoute::C_MANAGE_INVENTORY => [
            Constants\AdminRoute::AMIN_EDIT_AUCTION_BIDDER_AUTOCOMPLETE => 'core->admin->inventory->edit->auctionBidderAutocomplete->limit',
        ],
    ];

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
        /**
         * Search keyword defines filtering and sorting by relevance score calculated by levenshtein distance formula.
         */
        $searchKeyword = $paramFetcherForGet->getString(Constants\UrlParam::Q);
        /**
         * Context lot item id will be used to detect actual winning auction id of the lot, so it could be applied for strict filtering of winning bidders.
         */
        $contextLotItemId = $paramFetcherForGet->has(Constants\UrlParam::CONTEXT_LID)
            ? $paramFetcherForGet->getIntPositive(Constants\UrlParam::CONTEXT_LID)
            : null;
        /**
         * When filtering auction is explicitly applied, then it strictly filters out winning bidders who are not registered in this auction.
         */
        $filterAuctionId = $paramFetcherForGet->has(Constants\UrlParam::FILTER_AID)
            ? $paramFetcherForGet->getIntPositive(Constants\UrlParam::FILTER_AID)
            : null;
        /**
         * Context auction is useful for detecting bidder numbers of users who are registered in this auction, when strict auction filtering is skipped.
         */
        $contextAuctionId = $paramFetcherForGet->has(Constants\UrlParam::CONTEXT_AID)
            ? $paramFetcherForGet->getIntPositive(Constants\UrlParam::CONTEXT_AID)
            : null;
        /**
         * Account filtering restricts authorized user from accessing entities from another sub-domain.
         */
        $filterAccountId = $this->createFilterAccountDetector()
            ->detectFilterAccountId($this->getEditorUserId(), $this->getSystemAccountId());
        $input = AuctionBidderAutocompleteDataBuildingInput::new()->construct(
            $searchKeyword,
            $contextLotItemId,
            $filterAuctionId,
            $contextAuctionId,
            $filterAccountId,
            $this->detectLimit()
        );
        $data = $this->createAuctionBidderAutocompleteDataBuilder()->build($input);
        return $data;
    }

    protected function detectLimit(): int
    {
        $paramFetcherForRoute = $this->getParamFetcherForRoute();
        $controller = $paramFetcherForRoute->getControllerName();
        $action = $paramFetcherForRoute->getActionName();
        $caCollection = ControllerActionCollection::new()->construct(self::LIMITS);
        $optionKey = $caCollection->get($controller, $action);
        if (!$optionKey) {
            throw new RuntimeException("Option key for autocompleter limit not found" . composeSuffix(['controller' => $controller, 'action' => $action]));
        }
        $limit = $this->cfg()->get($optionKey);
        return $limit;
    }
}
