<?php
/**
 * SAM-10775: Create in Admin Web the "Tax Definition Edit" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 01, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\TaxDefinitionEditForm\RatePanel\Internal\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\TaxDefinitionRange\TaxDefinitionRangeReadRepositoryCreateTrait;

/**
 * Class DataProvider
 * @package Sam\View\Admin\Form\TaxDefinitionEditForm\RatePanel\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    use TaxDefinitionRangeReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Fetch rate ranges data for the edit form table
     *
     * @param int|null $taxDefinitionId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadRangeRows(?int $taxDefinitionId, bool $isReadOnlyDb = false): array
    {
        if (!$taxDefinitionId) {
            return [];
        }
        $ranges = $this->createTaxDefinitionRangeReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterTaxDefinitionId($taxDefinitionId)
            ->orderByAmount()
            ->select(['amount', 'fixed', 'percent', 'mode'])
            ->loadRows();
        return $ranges;
    }
}
