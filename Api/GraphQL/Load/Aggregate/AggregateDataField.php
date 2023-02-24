<?php
/**
 * SAM-10844: Extend parameter filtering on auction lots, my items level
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 22, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Load\Aggregate;

use Sam\Core\Service\CustomizableClass;

/**
 * Class AggregateDataField
 * @package Sam\Api\GraphQL\Load\Aggregate
 */
class AggregateDataField extends CustomizableClass
{
    public readonly string $dataField;
    public readonly string $alias;
    public readonly AggregateFunction $aggregateFunction;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(string $dataField, AggregateFunction $aggregateFunction, ?string $alias = null): static
    {
        $this->dataField = $dataField;
        $this->aggregateFunction = $aggregateFunction;
        $this->alias = $alias ?? $dataField;
        return $this;
    }
}
