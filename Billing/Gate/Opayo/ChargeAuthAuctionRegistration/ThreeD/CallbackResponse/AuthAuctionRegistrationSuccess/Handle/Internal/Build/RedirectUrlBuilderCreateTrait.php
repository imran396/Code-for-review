<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           May 18, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeAuthAuctionRegistration\ThreeD\CallbackResponse\AuthAuctionRegistrationSuccess\Handle\Internal\Build;

trait RedirectUrlBuilderCreateTrait
{
    protected ?RedirectUrlBuilder $redirectUrlBuilder = null;

    /**
     * @return RedirectUrlBuilder
     */
    protected function createRedirectUrlBuilder(): RedirectUrlBuilder
    {
        return $this->redirectUrlBuilder ?: RedirectUrlBuilder::new();
    }

    /**
     * @param RedirectUrlBuilder $redirectUrlBuilder
     * @return $this
     * @internal
     */
    public function setRedirectUrlBuilder(RedirectUrlBuilder $redirectUrlBuilder): static
    {
        $this->redirectUrlBuilder = $redirectUrlBuilder;
        return $this;
    }
}
