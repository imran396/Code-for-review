<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 29, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\ConsignorCommissionFeeListForm\Load;

use Sam\Core\Constants;
use Sam\Core\Constants\Admin\ConsignorCommissionFeeListFormConstants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\ConsignorCommissionFee\ConsignorCommissionFeeReadRepository;
use Sam\Storage\ReadRepository\Entity\ConsignorCommissionFee\ConsignorCommissionFeeReadRepositoryCreateTrait;
use Sam\View\Admin\Form\ConsignorCommissionFeeListForm\Load\Dto\ConsignorCommissionFeeDto;

/**
 * Class ConsignorCommissionFeeListLoader
 * @package Sam\View\Admin\Form\ConsignorCommissionFeeListForm\Load
 */
class ConsignorCommissionFeeListLoader extends CustomizableClass
{
    use ConsignorCommissionFeeReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Fetch array of consignor commission fee dtos for consignor commission fee list form
     *
     * @param array $accountIds
     * @param string $sortColumn
     * @param bool $sortAscending
     * @param int|null $limit
     * @param int $offset
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadDtos(
        array $accountIds,
        string $sortColumn = '',
        bool $sortAscending = true,
        ?int $limit = null,
        int $offset = 0,
        bool $isReadOnlyDb = false
    ): array {
        $repository = $this->createRepository($accountIds, $isReadOnlyDb)
            ->joinAccount()
            ->orderByRelatedEntityId()
            ->select(['ccf.id', 'ccf.name', 'ccf.created_on', 'ccf.related_entity_id', 'acc.name as account_name'])
            ->offset($offset);

        if ($limit) {
            $repository->limit($limit);
        }

        if ($sortColumn === ConsignorCommissionFeeListFormConstants::ORD_CREATED_ON) {
            $repository->orderByCreatedOn($sortAscending);
        } elseif ($sortColumn === ConsignorCommissionFeeListFormConstants::ORD_NAME) {
            $repository->orderByName($sortAscending);
        }

        $rows = $repository->loadRows();
        $dtos = array_map(
            static function (array $row) {
                return ConsignorCommissionFeeDto::new()->fromDbRow($row);
            },
            $rows
        );
        return $dtos;
    }

    /**
     * @param array $accountIds
     * @param bool $isReadOnlyDb
     * @return int
     */
    public function count(array $accountIds, bool $isReadOnlyDb = false): int
    {
        $qty = $this->createRepository($accountIds, $isReadOnlyDb)->count();
        return $qty;
    }

    /**
     * @param array $accountIds
     * @param bool $isReadOnlyDb
     * @return ConsignorCommissionFeeReadRepository
     */
    protected function createRepository(array $accountIds, bool $isReadOnlyDb = false): ConsignorCommissionFeeReadRepository
    {
        $repository = $this->createConsignorCommissionFeeReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterLevel(Constants\ConsignorCommissionFee::LEVEL_ACCOUNT)
            ->filterRelatedEntityId($accountIds);
        return $repository;
    }
}
