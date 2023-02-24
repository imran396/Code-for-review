<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           6/17/20
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Email\Load;

/**
 * Trait AuctionEmailTemplateLoaderAwareTrait
 * @package Sam\Email\Load
 */
trait AuctionEmailTemplateLoaderAwareTrait
{
    protected ?AuctionEmailTemplateLoader $auctionEmailTemplateLoader = null;

    /**
     * @return AuctionEmailTemplateLoader
     */
    protected function getAuctionEmailTemplateLoader(): AuctionEmailTemplateLoader
    {
        if ($this->auctionEmailTemplateLoader === null) {
            $this->auctionEmailTemplateLoader = AuctionEmailTemplateLoader::new();
        }
        return $this->auctionEmailTemplateLoader;
    }

    /**
     * @param AuctionEmailTemplateLoader $emailTemplateLoader
     * @return static
     * @internal
     */
    public function setAuctionEmailTemplateLoader(AuctionEmailTemplateLoader $emailTemplateLoader): static
    {
        $this->auctionEmailTemplateLoader = $emailTemplateLoader;
        return $this;
    }
}
