<?php
/**
 * SAM-9366: Refactor Sam\Bidder\AuctionBidder\CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 10, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Bidder\Internal\DetectUser;

/**
 * Trait UserDetectorCreateTrait
 * @package Sam\Import\Csv\Bidder\Internal\Load
 */
trait UserDetectorCreateTrait
{
    /**
     * @var UserDetector|null
     */
    protected ?UserDetector $userDetector = null;

    /**
     * @return UserDetector
     */
    protected function createUserDetector(): UserDetector
    {
        return $this->userDetector ?: UserDetector::new();
    }

    /**
     * @param UserDetector $userDetector
     * @return static
     * @internal
     */
    public function setUserDetector(UserDetector $userDetector): static
    {
        $this->userDetector = $userDetector;
        return $this;
    }
}
