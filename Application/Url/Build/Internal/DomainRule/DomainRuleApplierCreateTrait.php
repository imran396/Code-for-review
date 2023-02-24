<?php
/**
 * SAM-6649: Encapsulate url building values and parameters in config objects
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 23, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Internal\DomainRule;

/**
 * Trait DomainRuleApplierCreateTrait
 * @package Sam\Application\Url
 */
trait DomainRuleApplierCreateTrait
{
    /**
     * @var DomainRuleApplier|null
     */
    protected ?DomainRuleApplier $domainRuleApplier = null;

    /**
     * @return DomainRuleApplier
     */
    protected function createDomainRuleApplier(): DomainRuleApplier
    {
        return $this->domainRuleApplier ?: DomainRuleApplier::new();
    }

    /**
     * @param DomainRuleApplier $domainRuleApplier
     * @return $this
     * @internal
     */
    public function setDomainRuleApplier(DomainRuleApplier $domainRuleApplier): static
    {
        $this->domainRuleApplier = $domainRuleApplier;
        return $this;
    }
}
