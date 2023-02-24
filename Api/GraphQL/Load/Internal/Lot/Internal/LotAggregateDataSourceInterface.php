<?php
/**
 * SAM-10844: Extend parameter filtering on auction lots, my items level
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 17, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Load\Internal\Lot\Internal;

use Sam\Api\GraphQL\Load\Aggregate\AggregateDataField;

interface LotAggregateDataSourceInterface
{
    /**
     * @param AggregateDataField[] $aggregateFields
     */
    public function setAggregateFields(array $aggregateFields): self;
}
