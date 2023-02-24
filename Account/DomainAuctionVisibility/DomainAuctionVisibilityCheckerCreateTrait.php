<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/24/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Account\DomainAuctionVisibility;

/**
 * Trait DomainAuctionVisibilityCheckerCreateTrait
 * @package Sam\Account\DomainAuctionVisibility
 */
trait DomainAuctionVisibilityCheckerCreateTrait
{
    /**
     * @var VisibilityChecker|null
     */
    protected ?VisibilityChecker $domainAuctionVisibilityChecker = null;

    /**
     * @return VisibilityChecker
     */
    protected function createDomainAuctionVisibilityChecker(): VisibilityChecker
    {
        return $this->domainAuctionVisibilityChecker ?: VisibilityChecker::new();
    }

    /**
     * @param VisibilityChecker $domainAuctionVisibilityChecker
     * @return static
     * @internal
     */
    public function setDomainAuctionVisibilityChecker(VisibilityChecker $domainAuctionVisibilityChecker): static
    {
        $this->domainAuctionVisibilityChecker = $domainAuctionVisibilityChecker;
        return $this;
    }
}
