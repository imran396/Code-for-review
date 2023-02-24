<?php
/**
 * SAM-5397: Token Link SSO
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/19/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sso\TokenLink\Cli\Command\Create;

use Symfony\Component\Console\Input\InputOption;

/**
 * Class CreateConstants
 * @package
 */
class CreateConstants
{
    public const O_USERNAME = 'username';
    public const O_USERNAME_SHORT = 'u';
    public const O_USER_ID = 'userid';

    /** @var array */
    public static array $optionDefinitions = [
        [self::O_USERNAME, self::O_USERNAME_SHORT, InputOption::VALUE_REQUIRED, 'username'],
        [self::O_USER_ID, null, InputOption::VALUE_REQUIRED, 'user id'],
    ];
}
