<?php
/**
 * SAM-6573: Refactor lot list data sync providers - structurize responses
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 29, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants;

/**
 * Class LotDisplay
 * @package Sam\Core\Constants
 */
class LotDisplay
{
    /** Number of seconds before lot starts (int) */
    public const RES_SECONDS_BEFORE = 'sb';
    /** Number of seconds till lot ends (int) */
    public const RES_SECONDS_LEFT = 'sl';
}
