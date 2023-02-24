<?php
/**
 * SAM-9730: Refactor SMS notification module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sms\Template\Placeholder\AuctionLot\Internal;

/**
 * Trait PlaceholderRendererFactoryCreateTrait
 * @package Sam\Sms\Template\Placeholder\AuctionLot\Internal
 */
trait PlaceholderRendererFactoryCreateTrait
{
    protected ?PlaceholderRendererFactory $placeholderRendererFactory = null;

    /**
     * @return PlaceholderRendererFactory
     */
    protected function createPlaceholderRendererFactory(): PlaceholderRendererFactory
    {
        return $this->placeholderRendererFactory ?: PlaceholderRendererFactory::new();
    }

    /**
     * @param PlaceholderRendererFactory $placeholderRendererFactory
     * @return static
     * @internal
     */
    public function setPlaceholderRendererFactory(PlaceholderRendererFactory $placeholderRendererFactory): static
    {
        $this->placeholderRendererFactory = $placeholderRendererFactory;
        return $this;
    }
}
