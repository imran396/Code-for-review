<?php
/**
 * SAM-10121: Separate location auto-completer end-points per controllers and fix filtering by entity-context account
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 22, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\Location;

use RuntimeException;
use Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\Location\Internal\Build\LocationAutocompleteDataBuilderCreateTrait;
use Sam\Application\RequestParam\ParamFetcherForGetAwareTrait;
use Sam\Application\RequestParam\Route\ParamFetcherForRouteAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Web\ControllerAction\ControllerActionCollection;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class LocationAutocompleteResponseProducer
 * @package Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\Location
 */
class LocationAutocompleteResponseProducer extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use LocationAutocompleteDataBuilderCreateTrait;
    use ParamFetcherForGetAwareTrait;
    use ParamFetcherForRouteAwareTrait;

    /** @var string[][] */
    protected const LIMITS = [
        Constants\AdminRoute::C_MANAGE_AUCTIONS => [
            Constants\AdminRoute::AMA_LOCATION_AUTOCOMPLETE => 'core->admin->auction->locationAutocomplete->limit',
        ],
        Constants\AdminRoute::C_MANAGE_USERS => [
            Constants\AdminRoute::AMU_LOCATION_AUTOCOMPLETE => 'core->admin->user->edit->locationAutocomplete->limit',
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

    public function produce(int $filterAccountId): array
    {
        $paramFetcherForGet = $this->getParamFetcherForGet();
        $searchKeyword = $paramFetcherForGet->getString(Constants\UrlParam::Q);
        $data = $this->createLocationAutocompleteDataBuilder()
            ->build($searchKeyword, $filterAccountId, $this->detectLimit());
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
