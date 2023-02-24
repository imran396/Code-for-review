<?php
/**
 *
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 09, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Invoice\Legacy\InvoiceList\Csv\Internal\Load;

use LotItemCustData;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Lot\Load\LotCustomDataLoader;
use Sam\Invoice\Common\Load\InvoiceItem\Dto\InvoiceItemDto;
use Sam\Invoice\Common\Load\InvoiceItem\InvoiceItemLoader;

/**
 * Class DataProvider
 * @package Sam\Report\Invoice\Legacy\InvoiceList\Csv\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $invoiceId
     * @param bool $isReadOnlyDb
     * @return InvoiceItemDto[]
     */
    public function loadLotsRows(int $invoiceId, bool $isReadOnlyDb = false): array
    {
        return InvoiceItemLoader::new()->loadDtos($invoiceId, $isReadOnlyDb);
    }

    /**
     * @param array $lotItemCustomFieldIds
     * @param int $lotItemId
     * @param bool $isReadOnlyDb
     * @return LotItemCustData[]
     */
    public function loadLotCustomFields(
        array $lotItemCustomFieldIds,
        int $lotItemId,
        bool $isReadOnlyDb = false
    ): array {
        $lotCustomDataEntities = LotCustomDataLoader::new()
            ->loadEntities($lotItemCustomFieldIds, $lotItemId, $isReadOnlyDb);
        $lotCustomDataEntities = ArrayHelper::indexEntities($lotCustomDataEntities, 'LotItemCustFieldId');
        return $lotCustomDataEntities;
    }


}
