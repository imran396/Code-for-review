<?php
/**
 * SAM-10787: Create in Admin Web the "Tax Schema List" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 18, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\TaxSchemaListForm\Load;

use Sam\Core\Service\CustomizableClass;

/**
 * Class TaxSchemaListFilterCondition
 * @package Sam\View\Admin\Form\TaxSchemaListForm\Load
 */
class TaxSchemaListFilterCondition extends CustomizableClass
{
    public readonly array $accountIds;
    public readonly string $name;
    public readonly ?int $geoType;
    public readonly string $country;
    public readonly string $state;
    public readonly string $county;
    public readonly string $city;
    public readonly array $amountSource;
//    public readonly ?bool $forInvoice;
//    public readonly ?bool $forSettlement;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        array $accountIds = [],
        string $name = '',
        ?int $geoType = null,
        string $country = '',
        string $state = '',
        string $county = '',
        string $city = '',
//        ?bool $forInvoice = null,
//        ?bool $forSettlement = null,
        array $amountSource = [],
    ): static {
        $this->accountIds = $accountIds;
        $this->name = $name;
        $this->geoType = $geoType;
        $this->country = $country;
        $this->state = $state;
        $this->county = $county;
        $this->city = $city;
//        $this->forInvoice = $forInvoice;
//        $this->forSettlement = $forSettlement;
        $this->amountSource = $amountSource;
        return $this;
    }
}
