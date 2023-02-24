<?php
/**
 * SAM-5706: Credit card expiry date builder
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           1/11/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\CreditCard\Build;

/**
 * Trait CcExpiryDateBuilderCreateTrait
 * @package
 */
trait CcExpiryDateBuilderCreateTrait
{
    /**
     * @var CcExpiryDateBuilder|null
     */
    protected ?CcExpiryDateBuilder $ccExpiryDateBuilder = null;

    /**
     * @return CcExpiryDateBuilder
     */
    protected function createCcExpiryDateBuilder(): CcExpiryDateBuilder
    {
        return $this->ccExpiryDateBuilder ?: CcExpiryDateBuilder::new();
    }

    /**
     * @param CcExpiryDateBuilder $ccExpiryDateBuilder
     * @return $this
     * @internal
     */
    public function setCcExpiryDateBuilder(CcExpiryDateBuilder $ccExpiryDateBuilder): static
    {
        $this->ccExpiryDateBuilder = $ccExpiryDateBuilder;
        return $this;
    }
}
