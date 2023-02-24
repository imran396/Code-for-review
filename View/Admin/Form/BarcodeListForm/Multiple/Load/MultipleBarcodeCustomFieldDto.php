<?php
/**
 * SAM-8836: Apply DTOs for Barcode List page at admin side
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 29, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BarcodeListForm\Multiple\Load;

use Sam\Core\Service\CustomizableClass;

/**
 * Class MultipleBarcodeCustomFieldDto
 * @package Sam\View\Admin\Form\BarcodeListForm\Multiple\Load
 */
class MultipleBarcodeCustomFieldDto extends CustomizableClass
{
    public readonly int $id;
    public readonly string $name;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $id
     * @param string $name
     * @return $this
     */
    public function construct(int $id, string $name): static
    {
        $this->id = $id;
        $this->name = $name;
        return $this;
    }

    /**
     * @param array $row
     * @return $this
     */
    public function fromDbRow(array $row): static
    {
        return $this->construct((int)$row['id'], (string)$row['name']);
    }
}
