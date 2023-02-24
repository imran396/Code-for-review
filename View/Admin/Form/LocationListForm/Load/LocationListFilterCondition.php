<?php
/**
 * SAM-10719: SAM 3.7 Taxes. Add Search/Filter panel at Account Location List page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 06, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LocationListForm\Load;

use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;

/**
 * Class LocationListFilterCondition
 * @package Sam\View\Admin\Form\LocationListForm\Load
 */
class LocationListFilterCondition extends CustomizableClass
{
    public readonly array $accountIds;
    public readonly string $name;
    public readonly string $country;
    public readonly string $state;
    public readonly string $city;
    public readonly string $county;
    public readonly string $address;
    public readonly string $zip;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        array $accountIds,
        string $name,
        string $country,
        string $state,
        string $city,
        string $county,
        string $address,
        string $zip
    ): static {
        $this->accountIds = ArrayCast::castInt($accountIds, Constants\Type::F_INT_POSITIVE);
        $this->name = $name;
        $this->country = $country;
        $this->state = $state;
        $this->city = $city;
        $this->county = $county;
        $this->address = $address;
        $this->zip = $zip;
        return $this;
    }
}
