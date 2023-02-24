<?php
/**
 * This interface defines clear() method, that should unset all class state values, that can be set by caller.
 * It doesn't suppose to assign default values of instance.
 * Defaults should be set by initInstance() call, if it is CustomizableClass with default state.
 *
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

namespace Sam\Core\Service;

/**
 * Interface FilterClearableInterface
 * @package Sam\Core\Service
 */
interface ClearableInterface
{

    /**
     * Clear class state
     * Method doesn't assign default values (call initInstance() for that).
     * Method should return self instance.
     * @return self
     */
    public function clear(): self;
}
