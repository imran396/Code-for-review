<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 29, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\ConsignorCommissionFeeListForm\Load\Dto;

use Sam\Core\Service\CustomizableClass;

/**
 * Data structure that contains data for consignor commission fee list form
 *
 * Class ConsignorCommissionFeeDto
 * @package Sam\View\Admin\Form\ConsignorCommissionFeeListForm\Load\Dto
 */
class ConsignorCommissionFeeDto extends CustomizableClass
{
    public readonly int $id;
    public readonly string $name;
    public readonly string $accountName;
    public readonly int $accountId;
    public readonly string $createdOnIso;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Construct dto from DB result
     *
     * @param array $row
     * @return static
     */
    public function fromDbRow(array $row): static
    {
        $this->accountId = (int)$row['related_entity_id'];
        $this->accountName = (string)$row['account_name'];
        $this->createdOnIso = (string)$row['created_on'];
        $this->id = (int)$row['id'];
        $this->name = (string)$row['name'];
        return $this;
    }

}
