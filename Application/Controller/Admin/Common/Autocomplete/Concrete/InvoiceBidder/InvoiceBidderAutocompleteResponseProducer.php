<?php
/**
 * SAM-10115: Refactor invoice bidder autocomplete
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 17, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\InvoiceBidder;

use RuntimeException;
use Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\InvoiceBidder\Internal\Build\InvoiceBidderAutocompleteDataBuilderCreateTrait;
use Sam\Application\Controller\Admin\Common\Autocomplete\Shared\Account\FilterAccountDetectorCreateTrait;
use Sam\Application\RequestParam\ParamFetcherForGetAwareTrait;
use Sam\Application\RequestParam\Route\ParamFetcherForRouteAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Web\ControllerAction\ControllerActionCollection;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Core\Constants;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class InvoiceBidderAutocompleteResponseProducer
 * @package Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\InvoiceBidder
 */
class InvoiceBidderAutocompleteResponseProducer extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use FilterAccountDetectorCreateTrait;
    use InvoiceBidderAutocompleteDataBuilderCreateTrait;
    use ParamFetcherForGetAwareTrait;
    use ParamFetcherForRouteAwareTrait;
    use SystemAccountAwareTrait;

    /** @var string[][] */
    protected const LIMITS = [
        Constants\AdminRoute::C_MANAGE_AUCTIONS => [
            Constants\AdminRoute::AMA_AUCTION_INVOICE_BIDDER_AUTOCOMPLETE => 'core->admin->auction->invoice->filter->bidderAutocomplete->limit',
        ],
        Constants\AdminRoute::C_MANAGE_INVOICES => [
            Constants\AdminRoute::AMI_BIDDER_AUTOCOMPLETE => 'core->admin->invoice->list->filter->bidderAutocomplete->limit',
        ],
        Constants\AdminRoute::C_MANAGE_STACKED_TAX_INVOICE => [
            Constants\AdminRoute::AMSTI_BIDDER_AUTOCOMPLETE => 'core->admin->invoice->list->filter->bidderAutocomplete->limit',
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

    public function produce(?int $editorUserId): array
    {
        $paramFetcherForGet = $this->getParamFetcherForGet();
        $searchKeyword = $paramFetcherForGet->getString(Constants\UrlParam::Q);
        $filterAuctionId = $paramFetcherForGet->getIntPositive(Constants\UrlParam::AID);
        $filterAccountId = $this->createFilterAccountDetector()
            ->detectFilterAccountId($editorUserId, $this->getSystemAccountId());
        $data = $this->createInvoiceBidderAutocompleteDataBuilder()
            ->build($searchKeyword, $filterAuctionId, $filterAccountId, $this->detectLimit());
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
