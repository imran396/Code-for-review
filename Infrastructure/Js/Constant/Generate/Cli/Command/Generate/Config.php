<?php
/**
 * SAM-8867: Modularize JS constants generation script
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Js\Constant\Generate\Cli\Command\Generate;

use Sam\Core\Constants;

/**
 * Class Config
 * @package Sam\Infrastructure\Js\Constant\Generate\Cli\Command\Generate
 */
class Config
{
    /** @var string[] */
    public const ALLOWED_NAMESPACES = [
        'Sam\Core\Constants'
    ];

    /** @var string[] */
    public const DENIED_CLASSES = [
        Constants\AdminRoute::class,
        Constants\ResponsiveRoute::class,
        Constants\Url::class,
        Constants\Db::class,
        Constants\DbLock::class,
    ];
}
