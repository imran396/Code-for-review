<?php
/**
 * General repository for TermsAndConditions entity
 *
 * SAM-3641: TermsAndConditions repository and manager
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Pavel Mitkovskiy <pmitkovskiy@samauctionsoftware.com>
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           07 Mar, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\ReadRepository\Entity\TermsAndConditions;

/**
 * Class TermsAndConditionsReadRepository
 * @package Sam\Storage\ReadRepository\Entity\TermsAndConditions
 */
class TermsAndConditionsReadRepository extends AbstractTermsAndConditionsReadRepository
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
