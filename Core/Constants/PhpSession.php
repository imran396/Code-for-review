<?php
/**
 * SAM-5608: Abort PHP NULL Session
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           1/22/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants;

/**
 * Class PhpSession
 * @package
 */
class PhpSession
{
    // Different application reactions on php session id problem
    public const IIR_RESET = 'reset';
    public const IIR_BLOCK = 'block';
    /** @var string[] */
    public static array $invalidIdReactions = [self::IIR_RESET, self::IIR_BLOCK];
}
