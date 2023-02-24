<?php
/**
 * SAM-9355: Refactor Domain Detector and Domain Redirector for unit testing
 * SAM-5139: Domain Detector class
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/25/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\DomainDestination;

trait DomainDestinationDetectorCreateTrait
{
    /**
     * @var DomainDestinationDetector|null
     */
    protected ?DomainDestinationDetector $domainDestinationDetector = null;

    /**
     * @return DomainDestinationDetector
     */
    protected function createDomainDestinationDetector(): DomainDestinationDetector
    {
        return $this->domainDestinationDetector ?: DomainDestinationDetector::new();
    }

    /**
     * @param DomainDestinationDetector $domainDestinationDetector
     * @return static
     * @internal
     */
    public function setDomainDestinationDetector(DomainDestinationDetector $domainDestinationDetector): static
    {
        $this->domainDestinationDetector = $domainDestinationDetector;
        return $this;
    }
}
