<?php
/**
 * SAM-5207: Account filtering detecting helper in application context
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           6/27/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Account\Filter;

/**
 * Trait AccountFilterDetectorAwareTrait
 * @package Sam\Account\Filter
 */
trait AccountApplicationFilterDetectorAwareTrait
{
    protected ?AccountApplicationFilterDetector $accountApplicationFilterDetector = null;

    /**
     * @return AccountApplicationFilterDetector
     */
    protected function getAccountApplicationFilterDetector(): AccountApplicationFilterDetector
    {
        if ($this->accountApplicationFilterDetector === null) {
            $this->accountApplicationFilterDetector = AccountApplicationFilterDetector::new();
        }
        return $this->accountApplicationFilterDetector;
    }

    /**
     * @param AccountApplicationFilterDetector $accountFilterDetector
     * @return static
     * @internal
     */
    public function setAccountApplicationFilterDetector(AccountApplicationFilterDetector $accountFilterDetector): static
    {
        $this->accountApplicationFilterDetector = $accountFilterDetector;
        return $this;
    }
}
