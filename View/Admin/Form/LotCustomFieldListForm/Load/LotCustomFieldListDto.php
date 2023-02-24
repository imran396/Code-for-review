<?php
/**
 * SAM-9175: Apply DTO's for Lot Custom Field List page at admin side
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotCustomFieldListForm\Load;

use Sam\Core\Service\CustomizableClass;

/**
 * Class LotCustomFieldListDto
 * @package Sam\View\Admin\Form\LotCustomFieldListForm\Load
 */
class LotCustomFieldListDto extends CustomizableClass
{
    public readonly string $access;
    public readonly string $fieldParameters;
    public readonly int $fieldType;
    public readonly int $id;
    public readonly int $inCatalog;
    public readonly string $name;
    public readonly int $orderNo;
    public readonly int $searchField;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $access
     * @param string $fieldParameters
     * @param int $fieldType
     * @param int $id
     * @param int $inCatalog
     * @param string $name
     * @param int $orderNo
     * @param int $searchField
     * @return $this
     */
    public function construct(
        string $access,
        string $fieldParameters,
        int $fieldType,
        int $id,
        int $inCatalog,
        string $name,
        int $orderNo,
        int $searchField
    ): static {
        $this->access = $access;
        $this->fieldParameters = $fieldParameters;
        $this->fieldType = $fieldType;
        $this->id = $id;
        $this->inCatalog = $inCatalog;
        $this->name = $name;
        $this->orderNo = $orderNo;
        $this->searchField = $searchField;
        return $this;
    }

    /**
     * @param array $row
     * @return $this
     */
    public function fromDbRow(array $row): static
    {
        return $this->construct(
            (string)$row['access'],
            (string)$row['field_parameters'],
            (int)$row['field_type'],
            (int)$row['id'],
            (int)$row['in_catalog'],
            (string)$row['name'],
            (int)$row['order_no'],
            (int)$row['search_field']
        );
    }
}
