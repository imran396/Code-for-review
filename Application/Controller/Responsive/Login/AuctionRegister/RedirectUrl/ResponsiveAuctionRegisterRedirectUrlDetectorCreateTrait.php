<?php
/**
 * SAM-11674: Ability to adjust public page routing. Prepare existing routes
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 08, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Login\AuctionRegister\RedirectUrl;

/**
 * Trait ResponsiveAuctionRegisterRedirectUrlDetectorCreateTrait
 * @package Sam\Application\Controller\Responsive\Login\AuctionRegister\RedirectUrl
 */
trait ResponsiveAuctionRegisterRedirectUrlDetectorCreateTrait
{
    protected ?ResponsiveAuctionRegisterRedirectUrlDetector $responsiveAuctionRegisterRedirectUrlDetector = null;

    /**
     * @return ResponsiveAuctionRegisterRedirectUrlDetector
     */
    protected function createResponsiveAuctionRegisterRedirectUrlDetector(): ResponsiveAuctionRegisterRedirectUrlDetector
    {
        return $this->responsiveAuctionRegisterRedirectUrlDetector ?: ResponsiveAuctionRegisterRedirectUrlDetector::new();
    }

    /**
     * @param ResponsiveAuctionRegisterRedirectUrlDetector $responsiveAuctionRegisterRedirectUrlDetector
     * @return static
     * @internal
     */
    public function setResponsiveAuctionRegisterRedirectUrlDetector(ResponsiveAuctionRegisterRedirectUrlDetector $responsiveAuctionRegisterRedirectUrlDetector): static
    {
        $this->responsiveAuctionRegisterRedirectUrlDetector = $responsiveAuctionRegisterRedirectUrlDetector;
        return $this;
    }
}
