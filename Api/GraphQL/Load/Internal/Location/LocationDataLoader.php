<?php
/**
 * SAM-10467: Implement a GraphQL nested structure for a single auction
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 29, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Load\Internal\Location;

use Sam\Application\Access\ApplicationAccessCheckerCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\Location\LocationReadRepository;
use Sam\Storage\ReadRepository\Entity\Location\LocationReadRepositoryCreateTrait;

/**
 * Class LocationDataLoader
 * @package Sam\Api\GraphQL\Load\Internal\Location
 */
class LocationDataLoader extends CustomizableClass
{
    use ApplicationAccessCheckerCreateTrait;
    use LocationReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function load(array $ids, array $fields, bool $isReadOnlyDb = false): array
    {
        if (!in_array('id', $fields, true)) {
            $fields[] = 'id';
        }
        $locations = $this->createLocationReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterId($ids)
            ->select($fields)
            ->loadRows();
        return ArrayHelper::produceIndexedArray($locations, 'id');
    }

    public function loadList(
        LocationFilterCondition $filterCondition,
        array $order,
        int $limit,
        int $offset,
        array $fields,
        int $editorUserId,
        int $systemAccountId,
        bool $isReadOnlyDb = false
    ): array {
        if (!in_array('id', $fields, true)) {
            $fields[] = 'id';
        }
        $repository = $this->constructRepositoryForLocationList(
            $filterCondition,
            $order,
            $limit,
            $offset,
            $fields,
            $editorUserId,
            $systemAccountId,
            $isReadOnlyDb
        );
        $locations = $repository->loadRows();
        return ArrayHelper::produceIndexedArray($locations, 'id');
    }

    public function loadListWithGrouping(
        LocationFilterCondition $filterCondition,
        array $order,
        int $limit,
        int $offset,
        array $fields,
        int $editorUserId,
        int $systemAccountId,
        bool $isReadOnlyDb = false
    ): array {
        $repository = $this->constructRepositoryForLocationList(
            $filterCondition,
            $order,
            $limit,
            $offset,
            $fields,
            $editorUserId,
            $systemAccountId,
            $isReadOnlyDb
        );
        $repository = $this->applyGrouping($repository, $fields);
        $locations = $repository->loadRows();
        return $locations;
    }

    protected function constructRepositoryForLocationList(
        LocationFilterCondition $condition,
        array $order,
        int $limit,
        int $offset,
        array $fields,
        int $editorUserId,
        int $systemAccountId,
        bool $isReadOnlyDb = false
    ): LocationReadRepository {
        $repository = $this->createLocationReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterEntityId(null)
            ->limit($limit)
            ->offset($offset)
            ->select($fields);

        if ($this->createApplicationAccessChecker()->isCrossDomainAdminOnMainAccountForMultipleTenant($editorUserId, $systemAccountId, true)) {
            $repository = $repository->filterAccountId($condition->accountId);
        } else {
            $repository = $repository->filterAccountId($systemAccountId);
        }

        $repository = $this->applyFilterCondition($repository, $condition);
        $repository = $this->applyOrder($repository, $order);

        return $repository;
    }

    protected function applyFilterCondition(LocationReadRepository $repository, LocationFilterCondition $condition): LocationReadRepository
    {
        $repository = $repository->likeName($condition->name->contain);
        $repository = $repository->filterName($condition->name->in);
        $repository = $repository->skipName($condition->name->notIn);

        $repository = $repository->likeAddress($condition->address->contain);
        $repository = $repository->filterAddress($condition->address->in);
        $repository = $repository->skipAddress($condition->address->notIn);

        $repository = $repository->likeCountry($condition->country->contain);
        $repository = $repository->filterCountry($condition->country->in);
        $repository = $repository->skipCountry($condition->country->notIn);

        $repository = $repository->likeCity($condition->city->contain);
        $repository = $repository->filterCity($condition->city->in);
        $repository = $repository->skipCity($condition->city->notIn);

        $repository = $repository->likeCounty($condition->county->contain);
        $repository = $repository->filterCounty($condition->county->in);
        $repository = $repository->skipCounty($condition->county->notIn);

        $repository = $repository->likeState($condition->state->contain);
        $repository = $repository->filterState($condition->state->in);
        $repository = $repository->skipState($condition->state->notIn);

        $repository = $repository->likeZip($condition->zip->contain);
        $repository = $repository->filterZip($condition->zip->in);
        $repository = $repository->skipZip($condition->zip->notIn);
        return $repository;
    }

    protected function applyOrder(LocationReadRepository $repository, array $order): LocationReadRepository
    {
        foreach ($order as $field => $direction) {
            $repository = $repository->order(Constants\Db::A_LOCATION . '.' . $field, $direction === 'ASC');
        }
        return $repository;
    }

    protected function applyGrouping(LocationReadRepository $repository, array $fields): LocationReadRepository
    {
        foreach ($fields as $field) {
            $repository->qb()->group(Constants\Db::A_LOCATION . '.' . $field);
        }
        return $repository;
    }
}
