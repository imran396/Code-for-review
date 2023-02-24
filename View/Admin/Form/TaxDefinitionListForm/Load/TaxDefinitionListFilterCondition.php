<?php
/**
 * SAM-10782: Create in Admin Web the "Tax Definition List" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 05, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\TaxDefinitionListForm\Load;

use Sam\Core\Service\CustomizableClass;

/**
 * Class TaxDefinitionFilterCondition
 * @package Sam\View\Admin\Form\TaxDefinitionListForm\Load
 */
class TaxDefinitionListFilterCondition extends CustomizableClass
{
    public readonly array $accountIds;
    public readonly string $name;
    public readonly ?int $taxType;
    public readonly ?int $geoType;
    public readonly string $country;
    public readonly string $state;
    public readonly string $county;
    public readonly string $city;

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
        ?int $taxType = null,
        ?int $geoType = null,
        string $country = '',
        string $state = '',
        string $county = '',
        string $city = ''
    ): static {
        $this->accountIds = $accountIds;
        $this->name = $name;
        $this->taxType = $taxType;
        $this->geoType = $geoType;
        $this->country = $country;
        $this->state = $state;
        $this->county = $county;
        $this->city = $city;
        return $this;
    }
}
