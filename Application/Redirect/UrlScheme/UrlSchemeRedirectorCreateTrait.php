<?php
/**
 * SAM-9507: Move url scheme redirection to controller layer
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 28, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Redirect\UrlScheme;

/**
 * Trait UrlSchemeRedirectorCreateTrait
 */
trait UrlSchemeRedirectorCreateTrait
{
    /**
     * @var UrlSchemeRedirector|null
     */
    protected ?UrlSchemeRedirector $urlSchemeRedirector = null;

    /**
     * @return UrlSchemeRedirector
     */
    protected function createUrlSchemeRedirector(): UrlSchemeRedirector
    {
        return $this->urlSchemeRedirector ?: UrlSchemeRedirector::new();
    }

    /**
     * @param UrlSchemeRedirector $urlSchemeRedirector
     * @return $this
     * @internal
     */
    public function setUrlSchemeRedirector(UrlSchemeRedirector $urlSchemeRedirector): static
    {
        $this->urlSchemeRedirector = $urlSchemeRedirector;
        return $this;
    }
}
