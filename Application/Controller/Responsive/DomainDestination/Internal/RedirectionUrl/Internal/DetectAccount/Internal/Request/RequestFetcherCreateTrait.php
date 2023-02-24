<?php
/**
 * SAM-9355: Refactor Domain Detector and Domain Redirector for unit testing
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

namespace Sam\Application\Controller\Responsive\DomainDestination\Internal\RedirectionUrl\Internal\DetectAccount\Internal\Request;

/**
 * Trait RequestFetcherCreateTrait
 * @package Sam\Application\Controller\Responsive\DomainDestination\Internal\RedirectionUrl\Internal\Request
 */
trait RequestFetcherCreateTrait
{
    /**
     * @var RequestFetcher|null
     */
    protected ?RequestFetcher $requestFetcher = null;

    /**
     * @return RequestFetcher
     */
    protected function createRequestFetcher(): RequestFetcher
    {
        return $this->requestFetcher ?: RequestFetcher::new();
    }

    /**
     * @param RequestFetcher $requestFetcher
     * @return $this
     * @internal
     */
    public function setRequestFetcher(RequestFetcher $requestFetcher): static
    {
        $this->requestFetcher = $requestFetcher;
        return $this;
    }
}
