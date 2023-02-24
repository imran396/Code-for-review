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

namespace Sam\Api\GraphQL\Load\Internal\TaxSchema;

use Sam\Api\GraphQL\Load\Internal\Common\Filter\StringFilterCondition;
use Sam\Core\Service\CustomizableClass;

/**
 * Class TaxSchemaFilterCondition
 * @package Sam\Api\GraphQL\Load\Internal\TaxSchema
 */
class TaxSchemaFilterCondition extends CustomizableClass
{
    public readonly array $accountId;
    public readonly array $geoType;
    public readonly StringFilterCondition $name;
    public readonly StringFilterCondition $country;
    public readonly StringFilterCondition $city;
    public readonly StringFilterCondition $county;
    public readonly StringFilterCondition $state;
    public readonly ?bool $forInvoice;
    public readonly ?bool $forSettlement;
    public readonly array $amountSource;

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
        array $geoType,
        StringFilterCondition $country,
        StringFilterCondition $city,
        StringFilterCondition $county,
        StringFilterCondition $state,
        ?bool $forInvoice,
        ?bool $forSettlement,
        array $amountSource
    ): static {
        $this->accountId = $accountId;
        $this->name = $name;
        $this->geoType = $geoType;
        $this->country = $country;
        $this->city = $city;
        $this->county = $county;
        $this->state = $state;
        $this->forInvoice = $forInvoice;
        $this->forSettlement = $forSettlement;
        $this->amountSource = $amountSource;
        return $this;
    }

    public function fromArgs(array $args): static
    {
        return self::new()->construct(
            accountId: $args['accountId'] ?? [],
            name: StringFilterCondition::new()->fromArgs($args['name'] ?? []),
            geoType: $args['geoType'] ?? [],
            country: StringFilterCondition::new()->fromArgs($args['country'] ?? []),
            city: StringFilterCondition::new()->fromArgs($args['city'] ?? []),
            county: StringFilterCondition::new()->fromArgs($args['county'] ?? []),
            state: StringFilterCondition::new()->fromArgs($args['state'] ?? []),
            forInvoice: $args['forInvoice'] ?? null,
            forSettlement: $args['forSettlement'] ?? null,
            amountSource: $args['amountSource'] ?? [],
        );
    }
}
