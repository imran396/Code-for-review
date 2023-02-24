<?php
/**
 * SAM-6758: Rtb console output builders
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Console\Admin\Internal\Url;

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
