<?php
/**
 * Multiple Barcode Custom Field Loader
 *
 * SAM-5876: Refactor data loader for Barcode List page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 4, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BarcodeListForm\Multiple\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\LotItemCustField\LotItemCustFieldReadRepositoryCreateTrait;

/**
 * Class MultipleBarcodeCustomFieldLoader
 */
class MultipleBarcodeCustomFieldLoader extends CustomizableClass
{
    use LotItemCustFieldReadRepositoryCreateTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return MultipleBarcodeCustomFieldDto[] - return values for Multiple Barcode Custom Fields
     */
    public function load(): array
    {
        $rows = $this->createLotItemCustFieldReadRepository()
            ->select(['id', 'name'])
            ->filterActive(true)
            ->filterBarcode(true)
            ->orderByOrder()
            ->loadRows();
        $dtos = [];
        foreach ($rows as $row) {
            $dtos[] = MultipleBarcodeCustomFieldDto::new()->fromDbRow($row);
        }
        return $dtos;
    }
}
