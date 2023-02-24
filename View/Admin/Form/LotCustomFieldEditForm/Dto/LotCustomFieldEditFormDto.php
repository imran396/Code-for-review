<?php
/**
 * SAM-6308: Refactor custom field management to separate modules
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul. 21, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotCustomFieldEditForm\Dto;

use Sam\Core\Dto\StringDto;

/**
 * Class LotCustomFieldEditFormDto
 * @package Sam\View\Admin\Form\LotCustomFieldEditForm\Dto
 *
 * @property array $lotCategories
 * @property string $access
 * @property string $autoComplete
 * @property string $barcode
 * @property string $barcodeAutoPopulate
 * @property string $barcodeType
 * @property string $fckEditor
 * @property string|int $id
 * @property string $inAdminCatalog
 * @property string $inAdminSearch
 * @property string $inCatalog
 * @property string $inInvoices
 * @property string $inSettlements
 * @property string $lotNumAutoFill
 * @property string $name
 * @property string $order
 * @property string $parameters
 * @property string $searchField
 * @property string $searchIndex
 * @property string $type
 * @property string $unique
 */
class LotCustomFieldEditFormDto extends StringDto
{
    /** @var string[] */
    protected array $availableFields = [
        'access',
        'autoComplete',
        'barcode',
        'barcodeAutoPopulate',
        'barcodeType',
        'fckEditor',
        'id',
        'inAdminCatalog',
        'inAdminSearch',
        'inCatalog',
        'inInvoices',
        'inSettlements',
        'lotCategories',
        'lotNumAutoFill',
        'name',
        'order',
        'parameters',
        'searchField',
        'searchIndex',
        'type',
        'unique',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return array
     */
    public function getAvailableFields(): array
    {
        return $this->availableFields;
    }
}
