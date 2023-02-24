<?php
/**
 *
 * SAM-4625: Refactor settlement list report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2018-12-26
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Settlement\SettlementList;

use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class DataLoader
 * @package Sam\Report\Settlement\SettlementList
 */
class DataLoader extends CustomizableClass
{
    use DbConnectionTrait;
    use FilterAwareTrait;
    use FilterAccountAwareTrait;
    use FilterAuctionAwareTrait;
    use NumberFormatterAwareTrait;
    use SettingsManagerAwareTrait;
    use SortInfoAwareTrait;
    use SystemAccountAwareTrait;
    use TimezoneLoaderAwareTrait;

    protected ?QueryBuilder $queryBuilder = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return array
     */
    public function load(): array
    {
        $query = $this->getQueryBuilder()->buildResultQuery();
        $this->query($query);
        $rows = $this->fetchAllAssoc();
        return $rows;
    }

    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder(): QueryBuilder
    {
        if ($this->queryBuilder === null) {
            $this->setQueryBuilder(QueryBuilder::new())
                ->initQueryBuilder();
        }
        return $this->queryBuilder;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @return static
     */
    protected function setQueryBuilder(QueryBuilder $queryBuilder): static
    {
        $this->queryBuilder = $queryBuilder;
        return $this;
    }

    /**
     * @return static
     */
    protected function initQueryBuilder(): static
    {
        $isChargeConsignorCommission = (bool)$this->getSettingsManager()
            ->get(Constants\Setting::CHARGE_CONSIGNOR_COMMISSION, $this->getSystemAccountId());
        $this->queryBuilder
            ->enableAccountFiltering($this->isAccountFiltering())
            ->enableAscendingOrder($this->isAscendingOrder())
            ->enableChargeConsignorCommission($isChargeConsignorCommission)
            ->filterAccountId($this->getFilterAccountId())
            ->filterAuctionId($this->getFilterAuctionId())
            ->filterConsignorUserId($this->getConsignorUserId())
            ->filterSettlementStatusId($this->getSettlementStatusId())
            ->setSortColumnIndex($this->getSortColumnIndex())
            ->setSystemAccountId($this->getSystemAccountId());
        return $this;
    }
}
