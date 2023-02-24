<?php
/**
 * SAM-10844: Extend parameter filtering on auction lots, my items level
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 21, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Load\Aggregate;

/**
 * Class AggregateFunction
 * @package Sam\Api\GraphQL\Load\Aggregate
 */
enum AggregateFunction
{
    case SUM;
    case AVG;
    case MIN;
    case MAX;
    case GROUP;
    case COUNT;

    public static function numericFunctions(): array
    {
        return [
            self::SUM,
            self::AVG,
            self::MIN,
            self::MAX
        ];
    }

    public function isNumeric(): bool
    {
        return in_array($this, self::numericFunctions(), true);
    }
}
