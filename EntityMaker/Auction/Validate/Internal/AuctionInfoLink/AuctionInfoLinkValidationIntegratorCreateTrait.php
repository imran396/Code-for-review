<?php
/**
 * SAM-10211: External Auction Info Link Breaking Auction Name Link in Invoice_Html
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           02-05, 2022
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Auction\Validate\Internal\AuctionInfoLink;

/**
 * Trait AuctionInfoLinkValidationIntegratorCreateTrait
 * @package Sam\EntityMaker\Auction\Validate\Internal\AuctionInfoLink
 */
trait AuctionInfoLinkValidationIntegratorCreateTrait
{
    protected ?AuctionInfoLinkValidationIntegrator $auctionInfoLinkValidationIntegrator = null;

    /**
     * @return AuctionInfoLinkValidationIntegrator
     */
    protected function createAuctionInfoLinkValidationIntegrator(): AuctionInfoLinkValidationIntegrator
    {
        return $this->auctionInfoLinkValidationIntegrator ?: AuctionInfoLinkValidationIntegrator::new();
    }

    /**
     * @param AuctionInfoLinkValidationIntegrator $auctionInfoLinkValidationIntegrator
     * @return $this
     * @internal
     */
    public function setAuctionInfoLinkValidationIntegrator(AuctionInfoLinkValidationIntegrator $auctionInfoLinkValidationIntegrator): static
    {
        $this->auctionInfoLinkValidationIntegrator = $auctionInfoLinkValidationIntegrator;
        return $this;
    }
}




