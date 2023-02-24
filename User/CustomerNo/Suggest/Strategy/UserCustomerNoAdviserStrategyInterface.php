<?php
/**
 * SAM-4666: User customer no adviser
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan. 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\CustomerNo\Suggest\Strategy;

/**
 * Interface UserCustomerNoAdviserStrategyInterface
 * @package Sam\User\CustomerNo\Suggest\Strategy
 */
interface UserCustomerNoAdviserStrategyInterface
{
    /**
     * @param array $optionals
     * @return self
     */
    public function construct(array $optionals = []): self;

    /**
     * @return int
     */
    public function suggest(): int;
}
