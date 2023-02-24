<?php
/**
 * SAM-8662: Adjustments for Bidder Number Padding and Adviser services and apply unit tests
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 27, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants;

class Bidder
{
    public const PCH_SPACE = ' ';
    public const PCH_ZERO = '0';
    public const PCH_NONE = '';
    public const PCH_DEFAULT = self::PCH_ZERO;
    /** @var string[] */
    public const PADDING_CHARACTERS = [self::PCH_ZERO, self::PCH_SPACE, self::PCH_NONE];
    /** @var string[] */
    public const PADDING_CHARACTER_NAMES = [
        self::PCH_SPACE => 'Space',
        self::PCH_ZERO => 'Zero',
        self::PCH_NONE => 'None',
    ];
}
