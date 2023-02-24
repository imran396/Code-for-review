<?php
/**
 * SAM-6397: Runtime config options
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 09, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants;

class Runtime
{
    public const KEY_DEFAULT = '__default__';
    public const KEY_PHP_VALUE = 'phpValue';
    public const KEY_NGINX = 'nginx';
    public const KEY_INI_SET = 'iniSet';
}
