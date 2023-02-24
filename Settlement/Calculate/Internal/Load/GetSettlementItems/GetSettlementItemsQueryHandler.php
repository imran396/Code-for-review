<?php
/**
 * SAM-6499: Refactor Settlement Calculator module (2020 year)
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 21, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Calculate\Internal\Load\GetSettlementItems;


use Sam\Core\Service\CustomizableClass;
use QMySqli5DatabaseResult;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Settlement\Calculate\Internal\Load\Dto\SettlementItemDto;

/**
 * Class GetSettlementItemsQueryHandler
 * @package Sam\Settlement\Calculate\Internal\Load\GetSettlementItems
 * @internal
 */
class GetSettlementItemsQueryHandler extends CustomizableClass
{
    use DbConnectionTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param GetSettlementItemsQuery $query
     * @return SettlementItemDto[]
     */
    public function handle(GetSettlementItemsQuery $query): array
    {
        $result = [];
        $queryString = $this->buildSqlQuery($query);
        $dbResult = $this->query($queryString);
        while ($row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC)) {
            $result[] = SettlementItemDto::new()->fromDbRow($row);
        }
        return $result;
    }

    /**
     * @param GetSettlementItemsQuery $query
     * @return string
     */
    protected function buildSqlQuery(GetSettlementItemsQuery $query): string
    {
        $queryString = <<<SQL
SELECT si.hammer_price      AS HP,
       si.lot_item_id       AS lot_item_id,
       si.auction_id        AS auction_id,
       li.winning_bidder_id AS winning_bidder_id,
       a.auction_held_in    AS auction_country,
       ii.invoice_id,
       ii.sales_tax
FROM settlement_item AS si
         INNER JOIN lot_item AS li ON li.id = si.lot_item_id
         LEFT JOIN auction AS a ON a.id = si.auction_id
         LEFT JOIN invoice_item AS ii ON si.auction_id = ii.auction_id
                                        AND si.lot_item_id = ii.lot_item_id
                                        AND ii.active = true
                                        AND ii.release = false
         LEFT JOIN invoice AS i ON i.id = ii.invoice_id
                                AND i.invoice_status_id NOT IN (
                                {$this->escape(Constants\Invoice::IS_CANCELED)}, 
                                {$this->escape(Constants\Invoice::IS_DELETED)}
                                )
WHERE  
            si.settlement_id = {$this->escape($query->getSettlementId())}
            AND si.active = TRUE
SQL;
        return $queryString;
    }
}
