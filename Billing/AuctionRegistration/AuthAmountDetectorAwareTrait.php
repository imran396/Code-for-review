<?php
/**
 * SAM-4932: Authorization/Capture must skip when Authorization Amount is selected as '0' at Auction Level
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           05/18/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\AuctionRegistration;

/**
 * Trait AuthAmountDetectorAwareTrait
 * @package Sam\Billing\AuctionRegistration
 */
trait AuthAmountDetectorAwareTrait
{
    /**
     * @var AuthAmountDetector|null
     */
    protected ?AuthAmountDetector $authAmountDetector = null;

    /**
     * @return AuthAmountDetector
     */
    protected function getAuthAmountDetector(): AuthAmountDetector
    {
        if ($this->authAmountDetector === null) {
            $this->authAmountDetector = AuthAmountDetector::new();
        }
        return $this->authAmountDetector;
    }

    /**
     * @param AuthAmountDetector $authAmountDetector
     * @return static
     * @internal
     */
    public function setAuthAmountDetector(AuthAmountDetector $authAmountDetector): static
    {
        $this->authAmountDetector = $authAmountDetector;
        return $this;
    }
}
