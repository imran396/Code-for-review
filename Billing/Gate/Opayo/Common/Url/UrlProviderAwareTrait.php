<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           Oct 22, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\Common\Url;

/**
 * Trait UrlProviderAwareTrait
 */
trait UrlProviderAwareTrait
{
    /**
     * @var UrlProvider|null
     */
    protected ?UrlProvider $urlProvider = null;

    /**
     * @return UrlProvider
     */
    protected function getUrlProvider(): UrlProvider
    {
        if ($this->urlProvider === null) {
            $this->urlProvider = UrlProvider::new();
        }
        return $this->urlProvider;
    }

    /**
     * @param UrlProvider $urlProvider
     * @return $this
     * @internal
     */
    public function setUrlProvider(UrlProvider $urlProvider): static
    {
        $this->urlProvider = $urlProvider;
        return $this;
    }
}
