<?php
/**
 * SAM-6481: Add bidding options on the admin - auction - lots - added lots table
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 23, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\PlaceBid;

/**
 * Common interface for place bid command validator.
 * Validators may differ for different types of auctions and bids
 *
 * Interface PlaceBidValidatorInterface
 * @package Sam\Application\Controller\Admin\PlaceBid
 */
interface PlaceBidValidatorInterface
{
    /**
     * @param PlaceBidCommand $command
     * @return bool
     */
    public function validate(PlaceBidCommand $command): bool;

    /**
     * @return array
     */
    public function errorMessages(): array;
}
