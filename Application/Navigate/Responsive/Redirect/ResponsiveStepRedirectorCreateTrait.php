<?php
/**
 * SAM-5546: Auction registration step detection and redirect
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           12-01, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Navigate\Responsive\Redirect;

/**
 * Trait AuctionRegistrationStepRedirectorCreateTrait
 * @package Sam\Application\Navigate\Responsive\Redirect
 */
trait ResponsiveStepRedirectorCreateTrait
{
    /**
     * @var ResponsiveStepRedirector|null
     */
    protected ?ResponsiveStepRedirector $responsiveStepRedirector = null;

    /**
     * @return ResponsiveStepRedirector
     */
    protected function createResponsiveStepRedirector(): ResponsiveStepRedirector
    {
        return $this->responsiveStepRedirector ?: ResponsiveStepRedirector::new();
    }

    /**
     * @param ResponsiveStepRedirector $responsiveStepRedirector
     * @return static
     * @internal
     */
    public function setResponsiveStepRedirector(ResponsiveStepRedirector $responsiveStepRedirector): static
    {
        $this->responsiveStepRedirector = $responsiveStepRedirector;
        return $this;
    }
}
