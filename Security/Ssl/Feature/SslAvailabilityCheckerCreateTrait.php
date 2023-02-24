<?php
/**
 * SAM-9553: Apply ConfigRepository dependency
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Security\Ssl\Feature;

/**
 * Trait SslAvailabilityCheckerCreateTrait
 * @package Sam\Security\Ssl\Feature
 */
trait SslAvailabilityCheckerCreateTrait
{
    /**
     * @var SslAvailabilityChecker|null
     */
    protected ?SslAvailabilityChecker $sslAvailabilityChecker = null;

    /**
     * @return SslAvailabilityChecker
     */
    protected function createSslAvailabilityChecker(): SslAvailabilityChecker
    {
        return $this->sslAvailabilityChecker ?: SslAvailabilityChecker::new();
    }

    /**
     * @param SslAvailabilityChecker $sslAvailabilityChecker
     * @return $this
     * @internal
     */
    public function setSslAvailabilityChecker(SslAvailabilityChecker $sslAvailabilityChecker): static
    {
        $this->sslAvailabilityChecker = $sslAvailabilityChecker;
        return $this;
    }
}
