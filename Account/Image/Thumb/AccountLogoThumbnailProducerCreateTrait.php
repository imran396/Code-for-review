<?php
/**
 * SAM-7943: Refactor \Account_Image class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 24, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Account\Image\Thumb;

/**
 * Trait AccountLogoThumbnailProducerCreateTrait
 * @package Sam\Account\Image
 */
trait AccountLogoThumbnailProducerCreateTrait
{
    protected ?AccountLogoThumbnailProducer $accountLogoThumbnailProducer = null;

    /**
     * @return AccountLogoThumbnailProducer
     */
    protected function createAccountLogoThumbnailProducer(): AccountLogoThumbnailProducer
    {
        return $this->accountLogoThumbnailProducer ?: AccountLogoThumbnailProducer::new();
    }

    /**
     * @param AccountLogoThumbnailProducer $accountLogoThumbnailProducer
     * @return static
     * @internal
     */
    public function setAccountLogoThumbnailProducer(AccountLogoThumbnailProducer $accountLogoThumbnailProducer): static
    {
        $this->accountLogoThumbnailProducer = $accountLogoThumbnailProducer;
        return $this;
    }
}
