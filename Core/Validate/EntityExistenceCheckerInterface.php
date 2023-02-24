<?php
/**
 * SAM-4922: Entity Loader and Existence Checker approach integration
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           3/24/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Validate;

use Sam\Core\Service\ClearableInterface;
use Sam\Core\Service\CustomizableClassInterface;

/**
 * Interface EntityExistenceCheckerInterface
 * @package Sam\Core\Validate
 */
interface EntityExistenceCheckerInterface extends ClearableInterface, CustomizableClassInterface
{
    // Currently it doesn't supply own methods
}
