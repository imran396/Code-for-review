<?php
/**
 * SAM-10119: Refactor RTB bidder autocomplete
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 20, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\RtbBidder;

use RuntimeException;
use Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\RtbBidder\Internal\Build\RtbBidderAutocompleteDataBuilderCreateTrait;
use Sam\Application\Controller\Admin\Common\Autocomplete\Shared\Account\FilterAccountDetectorCreateTrait;
use Sam\Application\RequestParam\ParamFetcherForGetAwareTrait;
use Sam\Application\RequestParam\Route\ParamFetcherForRouteAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Web\ControllerAction\ControllerActionCollection;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class RtbBidderAutocompleteResponseProducer
 * @package Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\RtbBidder
 */
class RtbBidderAutocompleteResponseProducer extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use FilterAccountDetectorCreateTrait;
    use ParamFetcherForGetAwareTrait;
    use ParamFetcherForRouteAwareTrait;
    use RtbBidderAutocompleteDataBuilderCreateTrait;
    use SystemAccountAwareTrait;

    /** @var string[][] */
    protected const LIMITS = [
        Constants\AdminRoute::C_MANAGE_AUCTIONS => [
            Constants\AdminRoute::AMA_RTB_BIDDER_AUTOCOMPLETE => 'core->admin->auction->rtb->bidderAutocomplete->limit',
        ],
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $editorUserId
     * @return array
     */
    public function produce(?int $editorUserId): array
    {
        $paramFetcherForGet = $this->getParamFetcherForGet();
        $searchKeyword = $paramFetcherForGet->getString(Constants\UrlParam::Q);
        $auctionId = $paramFetcherForGet->getIntPositive(Constants\UrlParam::AID);
        $filterAccountId = $this->createFilterAccountDetector()
            ->detectFilterAccountId($editorUserId, $this->getSystemAccountId());
        $data = $this->createRtbBidderAutocompleteDataBuilder()
            ->build($searchKeyword, $auctionId, $filterAccountId, $this->detectLimit());
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
