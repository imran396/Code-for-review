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

namespace Sam\Sms\Template\Placeholder\LotItem;

/**
 * Trait LotItemPlaceholderKeyProviderCreateTrait
 * @package Sam\Sms\Template\Placeholder\LotItem
 */
trait LotItemPlaceholderKeyProviderCreateTrait
{
    protected ?LotItemPlaceholderKeyProvider $lotItemPlaceholderKeyProvider = null;

    /**
     * @return LotItemPlaceholderKeyProvider
     */
    protected function createLotItemPlaceholderKeyProvider(): LotItemPlaceholderKeyProvider
    {
        return $this->lotItemPlaceholderKeyProvider ?: LotItemPlaceholderKeyProvider::new();
    }

    /**
     * @param LotItemPlaceholderKeyProvider $lotItemPlaceholderKeyProvider
     * @return static
     * @internal
     */
    public function setLotItemPlaceholderKeyProvider(LotItemPlaceholderKeyProvider $lotItemPlaceholderKeyProvider): static
    {
        $this->lotItemPlaceholderKeyProvider = $lotItemPlaceholderKeyProvider;
        return $this;
    }
}
