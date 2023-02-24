<?php
/**
 *  SAM-6923 : Login to bid, completing signup is not redirecting into the auction registration process
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Login\TargetUrl;

/**
 * Trait ResponsiveLoginTargetUrlDetectorCreateTrait
 * @package Sam\APplication\Controller\Responsive\Login\TargetUrl
 */
trait ResponsiveLoginTargetUrlDetectorCreateTrait
{
    /**
     * @var ResponsiveLoginTargetUrlDetector|null
     */
    protected ?ResponsiveLoginTargetUrlDetector $responsiveLoginTargetUrlDetector = null;

    /**
     * @return ResponsiveLoginTargetUrlDetector
     */
    protected function createResponsiveLoginTargetUrlDetector(): ResponsiveLoginTargetUrlDetector
    {
        return $this->responsiveLoginTargetUrlDetector ?: ResponsiveLoginTargetUrlDetector::new();
    }

    /**
     * @param ResponsiveLoginTargetUrlDetector $responsiveLoginTargetUrlDetector
     * @return $this
     * @internal
     */
    public function setResponsiveLoginTargetUrlDetector(ResponsiveLoginTargetUrlDetector $responsiveLoginTargetUrlDetector): static
    {
        $this->responsiveLoginTargetUrlDetector = $responsiveLoginTargetUrlDetector;
        return $this;
    }
}
