<?php
/**
 * Check Printing for Settlements: Implementation of html layout and view layer: SAM-9795
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           10-31, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SettlementCheckEditForm\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\SettlementCheck\SettlementCheckReadRepositoryCreateTrait;
use SettlementCheck;

/**
 * Class DataProvider
 * @package Sam\View\Admin\Form\SettlementCheckEditForm\Load
 */
class DataProvider extends CustomizableClass
{
    use SettlementCheckReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function load(int $settlementCheckId, bool $isReadOnlyDb = false): ?SettlementCheck
    {
        $repo = $this->createSettlementCheckReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterId($settlementCheckId);

        $settlementCheck = $repo->loadEntity();
        return $settlementCheck;
    }
}
