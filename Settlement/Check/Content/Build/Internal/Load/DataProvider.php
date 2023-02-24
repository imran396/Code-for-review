<?php
/**
 * SAM-9766: Check Printing for Settlements: Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Content\Build\Internal\Load;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Load\Exception\CouldNotFindSettlement;
use Sam\Settlement\Load\SettlementLoader;

/**
 * Class DataProvider
 * @package Sam\Settlement\Check
 */
class DataProvider extends CustomizableClass
{
    private ?SettlementData $settlementData = null;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadSettlementData(int $settlementId, bool $isReadOnlyDb = false): SettlementData
    {
        if ($this->settlementData === null) {
            $row = SettlementLoader::new()->loadSelected(['account_id', 'consignor_id'], $settlementId, $isReadOnlyDb);
            if (!$row) {
                throw CouldNotFindSettlement::withId($settlementId);
            }
            $this->settlementData = SettlementData::new()->construct(
                $settlementId,
                Cast::toInt($row['account_id'] ?? null),
                Cast::toInt($row['consignor_id'] ?? null)
            );
        }
        return $this->settlementData;
    }
}
