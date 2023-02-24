<?php
/**
 * Web application layer service that provides data for consignor auto-completer component.
 * Since it is web application layer, it can fetch parameters from web request, authorized user from user session, system account from Application singleton.
 *
 * SAM-10099: Distinguish consignor autocomplete data loading end-points for different pages
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 10, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\Consignor;

use RuntimeException;
use Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\Consignor\Internal\Build\ConsignorAutocompleteDataBuilderCreateTrait;
use Sam\Application\Controller\Admin\Common\Autocomplete\Shared\Account\FilterAccountDetectorCreateTrait;
use Sam\Application\RequestParam\ParamFetcherForGetAwareTrait;
use Sam\Application\RequestParam\Route\ParamFetcherForRouteAwareTrait;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Core\Web\ControllerAction\ControllerActionCollection;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class ConsignorAutocompleteResponseProducer
 * @package Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\Consignor
 */
class ConsignorAutocompleteResponseProducer extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use ConsignorAutocompleteDataBuilderCreateTrait;
    use EditorUserAwareTrait;
    use FilterAccountDetectorCreateTrait;
    use ParamFetcherForGetAwareTrait;
    use ParamFetcherForRouteAwareTrait;
    use SystemAccountAwareTrait;

    /** @var string[][] */
    protected const LIMITS = [
        Constants\AdminRoute::C_MANAGE_AUCTIONS => [
            Constants\AdminRoute::AMA_LOT_LIST_QUICK_EDIT_CONSIGNOR_AUTOCOMPLETE => 'core->admin->auction->lots->quickEdit->consignorAutocomplete->limit',
            Constants\AdminRoute::AMA_EDIT_LOT_CONSIGNOR_AUTOCOMPLETE => 'core->admin->auction->editLot->consignorAutocomplete->limit',
        ],
        Constants\AdminRoute::C_MANAGE_INVENTORY => [
            Constants\AdminRoute::AMIN_LIST_FILTER_CONSIGNOR_AUTOCOMPLETE => 'core->admin->inventory->list->filter->consignorAutocomplete->limit',
            Constants\AdminRoute::AMIN_EDIT_CONSIGNOR_AUTOCOMPLETE => 'core->admin->inventory->edit->consignorAutocomplete->limit',
        ],
        Constants\AdminRoute::C_MANAGE_REPORTS => [
            Constants\AdminRoute::AMR_CONSIGNORS_FILTER_CONSIGNOR_AUTOCOMPLETE => 'core->admin->report->consignor->filter->consignorAutocomplete->limit',
        ],
        Constants\AdminRoute::C_MANAGE_SETTLEMENTS => [
            Constants\AdminRoute::AMS_LIST_GENERATE_CONSIGNOR_AUTOCOMPLETE => 'core->admin->settlement->list->generate->consignorAutocomplete->limit'
        ]
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
        [$searchKeyword] = $this->fetchRequestParams();
        $filterAccountId = $this->createFilterAccountDetector()
            ->detectFilterAccountId($this->getEditorUserId(), $this->getSystemAccountId());
        $data = $this->createConsignorAutocompleteDataBuilder()
            ->build($searchKeyword, $filterAccountId, $this->detectLimit());
        return $data;
    }

    protected function fetchRequestParams(): array
    {
        $paramFetcherForGet = $this->getParamFetcherForGet();
        $searchKeyword = $paramFetcherForGet->getString(Constants\UrlParam::Q);
        return [$searchKeyword];
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
