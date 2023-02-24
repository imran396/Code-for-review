<?php
/**
 * SAM-9888: Check Printing for Settlements: Bulk Checks Processing - Account level, Settlements List level (Part 2)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SettlementCheckCreateBatchForm\Load;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Check\Calculate\SettlementCheckCalculator;
use Sam\Settlement\Check\Content\Build\SettlementCheckContentRenderer;
use Sam\Settlement\Load\SettlementLoader;

/**
 * Class DataProvider
 * @package Sam\View\Admin\Form\SettlementCheckCreateBatchForm\Load
 */
class DataProvider extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function calcSettlementCheckRemainingAmount(int $settlementId, bool $isReadOnlyDb = false): float
    {
        return SettlementCheckCalculator::new()->calcRemainingAmount($settlementId, null, $isReadOnlyDb);
    }

    public function createContentForSettlements(array $settlementIds, bool $isReadOnlyDb = false): array
    {
        $settlementChecksContent = [];
        $settlements = SettlementLoader::new()->loadSelectedRows(['s.id', 'account_id', 'consignor_id', 'settlement_no'], $settlementIds);
        foreach ($settlements as $settlement) {
            $settlementChecksContent[] = NewSettlementCheckContent::new()->construct(
                (int)$settlement['id'],
                Cast::toInt($settlement['settlement_no']),
                SettlementCheckContentRenderer::new()->renderAllForNewCheck(
                    (int)$settlement['id'],
                    (int)$settlement['consignor_id'],
                    (int)$settlement['account_id'],
                    $isReadOnlyDb
                )
            );
        }
        return $settlementChecksContent;
    }
}
