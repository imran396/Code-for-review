<?php
/**
 * Trait that implements terms and conditions manager property and accessors
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           28 Mar, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settings;

/**
 * Trait TermsAndConditionsManagerAwareTrait
 * @package Sam\Settings
 */
trait TermsAndConditionsManagerAwareTrait
{
    /**
     * @var TermsAndConditionsManager|null
     */
    protected ?TermsAndConditionsManager $termsAndConditionsManager = null;

    /**
     * @param TermsAndConditionsManager $termsAndConditionsManager
     * @return static
     */
    public function setTermsAndConditionsManager(TermsAndConditionsManager $termsAndConditionsManager): static
    {
        $this->termsAndConditionsManager = $termsAndConditionsManager;
        return $this;
    }

    /**
     * @return TermsAndConditionsManager
     */
    protected function getTermsAndConditionsManager(): TermsAndConditionsManager
    {
        if ($this->termsAndConditionsManager === null) {
            $this->termsAndConditionsManager = TermsAndConditionsManager::new();
        }
        return $this->termsAndConditionsManager;
    }
}
