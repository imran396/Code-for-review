<?php
/**
 *
 * SAM-4626: Refactor settlement line item report
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

namespace Sam\Report\Settlement\LineItem;

use Sam\Application\Access\ApplicationAccessCheckerCreateTrait;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Filter\Common\FilterDatePeriodAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class DataLoader
 * @package Sam\Report\Settlement\LineItem
 */
class DataLoader extends CustomizableClass
{
    use ApplicationAccessCheckerCreateTrait;
    use DbConnectionTrait;
    use FilterAccountAwareTrait;
    use FilterDatePeriodAwareTrait;
    use SystemAccountAwareTrait;

    protected bool $isAccountFiltering = false;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return bool
     */
    public function isAccountFiltering(): bool
    {
        return $this->isAccountFiltering;
    }

    public function enableAccountFiltering(bool $enable): static
    {
        $this->isAccountFiltering = $enable;
        return $this;
    }

    /**
     * @return array
     */
    public function load(): array
    {
        $query = $this->buildResultQuery();
        $this->query($query);
        $rows = $this->fetchAllAssoc();
        return $rows;
    }

    /**
     * @return string
     */
    protected function buildResultQuery(): string
    {
        // @formatter:off
        $query =
            "SELECT " .
                "s.settlement_no AS settlement_no, " .
                "si.lot_item_id AS lot_item_id, " .
                "si.lot_name AS lot_name, " .
                "a.test_auction AS test_auction, " .
                "li.item_num AS item_num, " .
                "li.item_num_ext AS item_num_ext, " .
                "a.id AS auction_id, " .
                "a.name AS auction_name, " .
                "a.sale_num AS sale_num, " .
                "a.sale_num_ext AS sale_num_ext, " .
                "a.auction_status_id AS auction_status_id, " .
                "ali.id AS alid, " .
                "ali.lot_num_prefix AS lot_num_prefix, " .
                "ali.lot_num AS lot_num, " .
                "ali.lot_num_ext AS lot_num_ext, " .
                "ali.quantity AS quantity, " .
                "ali.quantity_x_money AS quantity_x_money, " .
                "si.hammer_price AS hammer_price, " .
                "si.commission AS commission, " .
                "si.subtotal AS subtotal, " .
                "s.tax_exclusive AS tax_exclusive, " .
                "COALESCE(
                        ali.quantity_digits, 
                        li.quantity_digits, 
                        (SELECT lc.quantity_digits
                         FROM lot_category lc
                           INNER JOIN lot_item_category lic ON lc.id = lic.lot_category_id
                         WHERE lic.lot_item_id = li.id
                           AND lc.active = 1
                         ORDER BY lic.id
                         LIMIT 1), 
                        (SELECT seta.quantity_digits FROM setting_auction seta WHERE seta.account_id = li.account_id)
                    ) as quantity_scale " .
            "FROM " .
                "settlement_item AS si " .
            "INNER JOIN lot_item li " .
                "ON si.lot_item_id = li.id " .
            "INNER JOIN account acc ON li.account_id = acc.id AND acc.active " .
            "LEFT JOIN settlement s " .
                "ON s.id = si.settlement_id " .
            "LEFT JOIN auction_lot_item AS ali " .
                "ON ali.lot_item_id = si.lot_item_id " .
                "AND ali.auction_id = si.auction_id " .
                "AND ali.lot_status_id IN (" . implode(',', Constants\Lot::$availableLotStatuses) . ") " .
            "LEFT JOIN auction AS a " .
                "ON a.id = si.auction_id " .
            "WHERE si.active = true AND s.settlement_status_id != " . Constants\Settlement::SS_DELETED . " " .
                "AND s.created_on >= " . $this->escape($this->getFilterStartDateUtcIso()) . " " .
                "AND s.created_on <= " . $this->escape($this->getFilterEndDateUtcIso()) . " ";
        // @formatter:on

        if ($this->isAccountFiltering()) {
            $accountId = $this->getFilterAccountId();
            $query = $accountId
                ? $query . "AND s.account_id = " . $this->escape($accountId) . " "
                : $query . "AND s.account_id > 0 ";
        } else { //In case sam portal is disabled
            $query .= "AND s.account_id = " . $this->escape($this->getSystemAccountId()) . " ";
        }

        $query .= "ORDER BY " .
            "a.sale_num, ali.lot_num, ali.lot_num_ext, ali.lot_num_prefix ";

        return $query;
    }

}
