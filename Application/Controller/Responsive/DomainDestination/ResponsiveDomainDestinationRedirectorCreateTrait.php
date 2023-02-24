<?php
/**
 * SAM-9355 : Refactor Domain Detector and Domain Redirector for unit testing
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\DomainDestination;

/**
 * Trait ResponsiveDomainDestinationRedirectorCreateTrait
 * @package
 */
trait ResponsiveDomainDestinationRedirectorCreateTrait
{
    /**
     * @var ResponsiveDomainDestinationRedirector|null
     */
    protected ?ResponsiveDomainDestinationRedirector $responsiveDomainDestinationRedirector = null;

    /**
     * @return ResponsiveDomainDestinationRedirector
     */
    protected function createResponsiveDomainDestinationRedirector(): ResponsiveDomainDestinationRedirector
    {
        return $this->responsiveDomainDestinationRedirector ?: ResponsiveDomainDestinationRedirector::new();
    }

    /**
     * @param ResponsiveDomainDestinationRedirector $responsiveDomainDestinationRedirector
     * @return $this
     * @internal
     */
    public function setResponsiveDomainDestinationRedirector(ResponsiveDomainDestinationRedirector $responsiveDomainDestinationRedirector): static
    {
        $this->responsiveDomainDestinationRedirector = $responsiveDomainDestinationRedirector;
        return $this;
    }
}
