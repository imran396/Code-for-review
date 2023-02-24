<?php
/**
 * SAM-4922: Entity Loader and Existence Checker approach integration
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           3/1/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Validate;

use Sam\Core\Service\CustomizableClass;

/**
 * Class EntityExistenceCheckerBase
 * @package Sam\Core\Validate
 */
abstract class EntityExistenceCheckerBase extends CustomizableClass implements EntityExistenceCheckerInterface
{
    /**
     * @return static
     */
    public function clear(): static
    {
        return $this;
    }
}
