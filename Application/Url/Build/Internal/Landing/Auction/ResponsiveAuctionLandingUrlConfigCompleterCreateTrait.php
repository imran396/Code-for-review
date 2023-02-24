<?php
/**
 * SAM-6649: Encapsulate url building values and parameters in config objects
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 21, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Internal\Landing\Auction;

/**
 * Trait ResponsiveAuctionLandingUrlConfigCompleterCreateTrait
 * @package
 */
trait ResponsiveAuctionLandingUrlConfigCompleterCreateTrait
{
    /**
     * @var ResponsiveAuctionLandingUrlConfigCompleter|null
     */
    protected ?ResponsiveAuctionLandingUrlConfigCompleter $responsiveAuctionLandingUrlConfigCompleter = null;

    /**
     * @return ResponsiveAuctionLandingUrlConfigCompleter
     */
    protected function createResponsiveAuctionLandingUrlConfigCompleter(): ResponsiveAuctionLandingUrlConfigCompleter
    {
        return $this->responsiveAuctionLandingUrlConfigCompleter ?: ResponsiveAuctionLandingUrlConfigCompleter::new();
    }

    /**
     * @param ResponsiveAuctionLandingUrlConfigCompleter $responsiveAuctionLandingUrlConfigCompleter
     * @return $this
     * @internal
     */
    public function setResponsiveAuctionLandingUrlConfigCompleter(ResponsiveAuctionLandingUrlConfigCompleter $responsiveAuctionLandingUrlConfigCompleter): static
    {
        $this->responsiveAuctionLandingUrlConfigCompleter = $responsiveAuctionLandingUrlConfigCompleter;
        return $this;
    }
}
