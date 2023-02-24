<?php
/**
 * SAM-5853: Project path resolver
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           26/2/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

// Since this is early run service, we need explicitly load classes

$sysRoot = dirname(__DIR__, 7);
$classesRoot = $sysRoot . '/includes/classes';
require_once $classesRoot . '/Sam/Core/Service/CustomizableClass.php';
require_once $classesRoot . '/Sam/Core/Service/Optional/OptionalKeyConstants.php';
require_once $classesRoot . '/Sam/Core/Path/PathResolver.php';

use Sam\Core\Path\PathResolver;

/**
 * This is initial point for path resolving, it detects system root path by own location.
 * @return PathResolver
 */
function path(): PathResolver
{
    $sysRoot = dirname(__DIR__, 7);
    return PathResolver::new()->construct($sysRoot);
}
