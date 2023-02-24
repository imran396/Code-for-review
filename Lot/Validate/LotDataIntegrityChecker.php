<?php
/**
 * Search lot numbers, custom field duplicates for lot
 *
 * SAM-5069: Data integrity checker - there shall only be one active lot_item_cust_data record for one lot_item
 * and one lot_item_cust_field
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           30 Jul, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Validate;

use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\LotItem\LotItemReadRepository;
use Sam\Storage\ReadRepository\Entity\LotItem\LotItemReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\LotItemCustData\LotItemCustDataReadRepository;
use Sam\Storage\ReadRepository\Entity\LotItemCustData\LotItemCustDataReadRepositoryCreateTrait;

/**
 * Class LotDataIntegrityChecker
 * @package Sam\Lot\Validate
 */
class LotDataIntegrityChecker extends CustomizableClass
{
    use FilterAccountAwareTrait;
    use LotItemCustDataReadRepositoryCreateTrait;
    use LotItemReadRepositoryCreateTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return LotItemCustDataReadRepository
     */
    public function prepareCustomFieldDuplicateSearch(): LotItemCustDataReadRepository
    {
        $repo = $this->createLotItemCustDataReadRepository()
            ->select(
                [
                    'licf.name',
                    'li.id',
                    'COUNT(1) as count_records',
                    'li.account_id',
                    'acc.name as account_name',
                ]
            )
            ->joinLotItem()
            ->joinLotItemCustomField()
            ->joinAccount()
            ->filterActive(true)
            ->groupByLotItemCustFieldId()
            ->groupByLotItemId()
            ->having('COUNT(1) > 1')
            ->joinLotItemOrderByAccountId()
            ->orderByLotItemId()
            ->setChunkSize(200);

        if ($this->getFilterAccountId()) {
            $repo->joinLotItemFilterAccountId($this->getFilterAccountId());
        }

        return $repo;
    }

    /**
     * @return LotItemReadRepository
     */
    public function prepareLotNumbersDuplicateSearch(): LotItemReadRepository
    {
        $repo = $this->createLotItemReadRepository()
            ->select(
                [
                    'GROUP_CONCAT(li.name) as names',
                    'GROUP_CONCAT(li.id) as ids',
                    'CONCAT(li.item_num , li.item_num_ext) as item_num',
                    'COUNT(1) as count_records',
                    'li.account_id',
                    'acc.name as account_name',
                ]
            )
            ->joinAccount()
            ->filterActive(true)
            ->groupByItemNum()
            ->groupByItemNumExt()
            ->groupByAccountId()
            ->having('COUNT(1) > 1')
            ->orderByAccountId()
            ->orderById()
            ->setChunkSize(200);

        if ($this->getFilterAccountId()) {
            $repo->filterAccountId($this->getFilterAccountId());
        }

        return $repo;
    }
}
