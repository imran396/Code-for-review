<?php
/**
 * SAM-6853: Settings > System Parameters > User options - "Auto assign Preferred bidder privileges upon credit card update" condition is not working properly
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Save\Internal\AutoPreferredCreditCard;

/**
 * Trait AutoPreferredCreditCardEffectCheckingIntegratorCreateTrait
 * @package Sam\EntityMaker\User\Save\Internal\AutoPreferredCreditCard
 */
trait AutoPreferredCreditCardEffectCheckingIntegratorCreateTrait
{
    protected ?AutoPreferredCreditCardEffectCheckingIntegrator $autoPreferredCreditCardEffectCheckingIntegrator = null;

    /**
     * @return AutoPreferredCreditCardEffectCheckingIntegrator
     */
    protected function createAutoPreferredCreditCardEffectCheckingIntegrator(): AutoPreferredCreditCardEffectCheckingIntegrator
    {
        return $this->autoPreferredCreditCardEffectCheckingIntegrator ?: AutoPreferredCreditCardEffectCheckingIntegrator::new();
    }

    /**
     * @param AutoPreferredCreditCardEffectCheckingIntegrator $autoPreferredCreditCardEffectCheckingIntegrator
     * @return $this
     * @internal
     */
    public function setAutoPreferredCreditCardEffectCheckingIntegrator(AutoPreferredCreditCardEffectCheckingIntegrator $autoPreferredCreditCardEffectCheckingIntegrator): static
    {
        $this->autoPreferredCreditCardEffectCheckingIntegrator = $autoPreferredCreditCardEffectCheckingIntegrator;
        return $this;
    }
}
