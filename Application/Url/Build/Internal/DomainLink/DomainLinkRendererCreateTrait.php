<?php
/**
 * SAM-6649: Encapsulate url building values and parameters in config objects
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 18, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Internal\DomainLink;

/**
 * Trait DomainLinkRendererCreateTrait
 * @package Sam\Application\Url\Build\Internal\DomainLink
 */
trait DomainLinkRendererCreateTrait
{
    /**
     * @var DomainLinkRenderer|null
     */
    protected ?DomainLinkRenderer $domainLinkRenderer = null;

    /**
     * @return DomainLinkRenderer
     */
    protected function createDomainLinkRenderer(): DomainLinkRenderer
    {
        return $this->domainLinkRenderer ?: DomainLinkRenderer::new();
    }

    /**
     * @param DomainLinkRenderer $domainLinkRenderer
     * @return $this
     * @internal
     */
    public function setDomainLinkRenderer(DomainLinkRenderer $domainLinkRenderer): static
    {
        $this->domainLinkRenderer = $domainLinkRenderer;
        return $this;
    }
}
