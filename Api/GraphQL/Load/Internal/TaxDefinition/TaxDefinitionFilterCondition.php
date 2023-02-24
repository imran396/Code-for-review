<?php
/**
 * SAM-10782: Create in Admin Web the "Tax Definition List" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 06, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Load\Internal\TaxDefinition;

use Sam\Api\GraphQL\Load\Internal\Common\Filter\StringFilterCondition;
use Sam\Core\Service\CustomizableClass;

/**
 * Class TaxDefinitionFilterCondition
 * @package Sam\Api\GraphQL\Load\Internal\TaxDefinition
 */
class TaxDefinitionFilterCondition extends CustomizableClass
{
    public readonly array $accountId;
    public readonly array $taxType;
    public readonly array $geoType;
    public readonly StringFilterCondition $name;
    public readonly StringFilterCondition $country;
    public readonly StringFilterCondition $city;
    public readonly StringFilterCondition $county;
    public readonly StringFilterCondition $state;

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
        array $taxType,
        array $geoType,
        StringFilterCondition $country,
        StringFilterCondition $city,
        StringFilterCondition $county,
        StringFilterCondition $state,
    ): static {
        $this->accountId = $accountId;
        $this->name = $name;
        $this->taxType = $taxType;
        $this->geoType = $geoType;
        $this->country = $country;
        $this->city = $city;
        $this->county = $county;
        $this->state = $state;
        return $this;
    }

    public function fromArgs(array $args): static
    {
        return self::new()->construct(
            accountId: $args['accountId'] ?? [],
            name: StringFilterCondition::new()->fromArgs($args['name'] ?? []),
            taxType: $args['taxType'] ?? [],
            geoType: $args['geoType'] ?? [],
            country: StringFilterCondition::new()->fromArgs($args['country'] ?? []),
            city: StringFilterCondition::new()->fromArgs($args['city'] ?? []),
            county: StringFilterCondition::new()->fromArgs($args['county'] ?? []),
            state: StringFilterCondition::new()->fromArgs($args['state'] ?? []),
        );
    }
}
