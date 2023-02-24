<?php
/**
 * Location List Data Loader
 *
 * SAM-6234: Refactor Location List page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 25, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LocationListForm\Load;

use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\Location\LocationReadRepository;
use Sam\Storage\ReadRepository\Entity\Location\LocationReadRepositoryCreateTrait;
use Sam\View\Admin\Form\LocationListForm\LocationListConstants;

/**
 * Class LocationListDataLoader
 */
class LocationListDataLoader extends CustomizableClass
{
    use LimitInfoAwareTrait;
    use LocationReadRepositoryCreateTrait;
    use SortInfoAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return int - return value of Locations count
     */
    public function count(int $systemAccountId, ?LocationListFilterCondition $filterCondition = null): int
    {
        return $this->prepareLocationListRepository($systemAccountId, $filterCondition)->count();
    }

    /**
     * @param string $filterAddress TODO: remove with tabulator demo /admin/manage-location/list-tabulator
     * @return LocationListDto[] - return values for Locations
     */
    public function load(int $systemAccountId, string $filterAddress = '', ?LocationListFilterCondition $filterCondition = null): array
    {
        $repo = $this->prepareLocationListRepository($systemAccountId, $filterCondition);

        switch ($this->getSortColumn()) {
            case LocationListConstants::ORD_BY_NAME:
                $repo->orderByName($this->isAscendingOrder());
                break;
            case LocationListConstants::ORD_BY_ADDRESS:
                $repo->orderByAddress($this->isAscendingOrder());
                break;
            case LocationListConstants::ORD_BY_CREATED_ON:
                $repo->orderByCreatedOn($this->isAscendingOrder());
                break;
            case LocationListConstants::ORD_BY_ID:
                $repo->orderById($this->isAscendingOrder());
                break;
            case LocationListConstants::ORD_BY_COUNTRY:
                $repo->orderByCountry($this->isAscendingOrder());
                break;
            case LocationListConstants::ORD_BY_STATE:
                $repo->orderByState($this->isAscendingOrder());
                break;
            case LocationListConstants::ORD_BY_CITY:
                $repo->orderByCity($this->isAscendingOrder());
                break;
            case LocationListConstants::ORD_BY_COUNTY:
                $repo->orderByCounty($this->isAscendingOrder());
                break;
            case LocationListConstants::ORD_BY_ZIP:
                $repo->orderByZip($this->isAscendingOrder());
                break;
            case LocationListConstants::ORD_BY_ACCOUNT:
                $repo->joinAccountOrderByName($this->isAscendingOrder());
                break;
        }

        if ($filterAddress) {
            $repo->likeAddress($filterAddress);
        }

        if ($this->getOffset()) {
            $repo->offset($this->getOffset());
        }

        if ($this->getLimit()) {
            $repo->limit($this->getLimit());
        }

        $dtos = [];
        foreach ($repo->loadRows() as $row) {
            $dtos[] = LocationListDto::new()->fromDbRow($row);
        }
        return $dtos;
    }

    /**
     * @param int $systemAccountId
     * @param LocationListFilterCondition|null $filterCondition
     * @return LocationReadRepository
     */
    protected function prepareLocationListRepository(int $systemAccountId, ?LocationListFilterCondition $filterCondition = null): LocationReadRepository
    {
        $select = [
            'acc.name as account_name',
            'loc.address',
            'loc.city',
            'loc.country',
            'loc.county',
            'loc.created_on',
            'loc.id',
            'loc.logo',
            'loc.name',
            'loc.state',
            'loc.zip',
        ];
        $repository = $this->createLocationReadRepository()
            ->filterActive(true)
            ->filterEntityId(null)
            ->joinAccount()
            ->select($select);
        if ($filterCondition) {
            $repository->filterAccountId($filterCondition->accountIds);
            if ($filterCondition->name) {
                $repository = $repository->filterName($filterCondition->name);
            }
            if ($filterCondition->country) {
                $repository = $repository->filterCountry($filterCondition->country);
            }
            if ($filterCondition->state) {
                $repository = $repository->filterState($filterCondition->state);
            }
            if ($filterCondition->city) {
                $repository = $repository->filterCity($filterCondition->city);
            }
            if ($filterCondition->county) {
                $repository = $repository->filterCounty($filterCondition->county);
            }
            if ($filterCondition->address) {
                $repository = $repository->filterAddress($filterCondition->address);
            }
            if ($filterCondition->zip) {
                $repository = $repository->filterZip($filterCondition->zip);
            }
        } else {
            $repository = $repository->filterAccountId($systemAccountId);
        }
        return $repository;
    }
}
