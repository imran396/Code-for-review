<?php
/**
 * SAM-5139: Domain Detector class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 23, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Domain;

/**
 * Trait AccountDomainDetectorCreateTrait
 * @package Sam\Application\Url\Domain
 */
trait AccountDomainDetectorCreateTrait
{
    protected ?AccountDomainDetector $accountDomainDetector = null;

    /**
     * @return AccountDomainDetector
     */
    protected function createAccountDomainDetector(): AccountDomainDetector
    {
        return $this->accountDomainDetector ?: AccountDomainDetector::new();
    }

    /**
     * @param AccountDomainDetector $accountDomainDetector
     * @return static
     * @internal
     */
    public function setAccountDomainDetector(AccountDomainDetector $accountDomainDetector): static
    {
        $this->accountDomainDetector = $accountDomainDetector;
        return $this;
    }
}
