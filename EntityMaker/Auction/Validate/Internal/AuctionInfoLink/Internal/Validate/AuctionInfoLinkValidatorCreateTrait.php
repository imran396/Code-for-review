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

namespace Sam\EntityMaker\Auction\Validate\Internal\AuctionInfoLink\Internal\Validate;

/**
 * Trait AuctionInfoLinkValidatorCreateTrait
 * @package Sam\EntityMaker\Auction\Validate\Internal\AuctionInfoLink\Internal\Validate
 */
trait AuctionInfoLinkValidatorCreateTrait
{
    protected ?AuctionInfoLinkValidator $auctionInfoLinkValidator = null;

    /**
     * @return AuctionInfoLinkValidator
     */
    protected function createAuctionInfoLinkValidator(): AuctionInfoLinkValidator
    {
        return $this->auctionInfoLinkValidator ?: AuctionInfoLinkValidator::new();
    }

    /**
     * @param AuctionInfoLinkValidator $auctionInfoLinkValidator
     * @return $this
     * @internal
     */
    public function setAuctionInfoLinkValidator(AuctionInfoLinkValidator $auctionInfoLinkValidator): static
    {
        $this->auctionInfoLinkValidator = $auctionInfoLinkValidator;
        return $this;
    }
}




