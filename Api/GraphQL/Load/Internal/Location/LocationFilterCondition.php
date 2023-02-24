<?php
/**
 * SAM-10719: SAM 3.7 Taxes. Add Search/Filter panel at Account Location List page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 03, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Load\Internal\Location;

use Sam\Api\GraphQL\Load\Internal\Common\Filter\StringFilterCondition;
use Sam\Core\Service\CustomizableClass;

/**
 * Class LocationFilterCondition
 * @package Sam\Api\GraphQL\Load\Internal\Location
 */
class LocationFilterCondition extends CustomizableClass
{
    public readonly array $accountId;
    public readonly StringFilterCondition $name;
    public readonly StringFilterCondition $address;
    public readonly StringFilterCondition $country;
    public readonly StringFilterCondition $city;
    public readonly StringFilterCondition $county;
    public readonly StringFilterCondition $state;
    public readonly StringFilterCondition $zip;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        array $accountId,
        StringFilterCondition $name,
        StringFilterCondition $address,
        StringFilterCondition $country,
        StringFilterCondition $city,
        StringFilterCondition $county,
        StringFilterCondition $state,
        StringFilterCondition $zip
    ): static {
        $this->accountId = $accountId;
        $this->name = $name;
        $this->address = $address;
        $this->country = $country;
        $this->city = $city;
        $this->county = $county;
        $this->state = $state;
        $this->zip = $zip;
        return $this;
    }

    public function fromArgs(array $args): static
    {
        return self::new()->construct(
            accountId: $args['accountId'] ?? [],
            name: StringFilterCondition::new()->fromArgs($args['name'] ?? []),
            address: StringFilterCondition::new()->fromArgs($args['address'] ?? []),
            country: StringFilterCondition::new()->fromArgs($args['country'] ?? []),
            city: StringFilterCondition::new()->fromArgs($args['city'] ?? []),
            county: StringFilterCondition::new()->fromArgs($args['county'] ?? []),
            state: StringFilterCondition::new()->fromArgs($args['state'] ?? []),
            zip: StringFilterCondition::new()->fromArgs($args['zip'] ?? []),
        );
    }
}
