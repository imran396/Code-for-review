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

namespace Sam\Settlement\Calculate\Internal\Load;


use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Calculate\Internal\Load\Dto\SettlementItemDto;
use Sam\Settlement\Calculate\Internal\Load\Dto\SettlementTotalsDto;
use Sam\Settlement\Calculate\Internal\Load\GetSettlementItems\GetSettlementItemsQuery;
use Sam\Settlement\Calculate\Internal\Load\GetSettlementItems\GetSettlementItemsQueryHandler;
use Sam\Settlement\Calculate\Internal\Load\GetSettlementTotals\GetSettlementTotalsQuery;
use Sam\Settlement\Calculate\Internal\Load\GetSettlementTotals\GetSettlementTotalsQueryHandler;

/**
 * Class SettlementSummaryDataLoader
 * @package Sam\Settlement\Calculate\Internal\Load
 * @internal
 */
class SettlementSummaryDataLoader extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param GetSettlementTotalsQuery $query
     * @return SettlementTotalsDto|null
     * @internal
     */
    public function loadTotalsDto(GetSettlementTotalsQuery $query): ?SettlementTotalsDto
    {
        return GetSettlementTotalsQueryHandler::new()->handle($query);
    }

    /**
     * @param GetSettlementItemsQuery $query
     * @return SettlementItemDto[]
     */
    public function loadSettlementItemsDto(GetSettlementItemsQuery $query): array
    {
        return GetSettlementItemsQueryHandler::new()->handle($query);
    }
}
