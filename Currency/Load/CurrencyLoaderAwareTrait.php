<?php
/**
 * SAM-4560: Currency loaders
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/14/18
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Currency\Load;

/**
 * Trait CurrencyLoaderAwareTrait
 * @package Sam\Currency\Load
 */
trait CurrencyLoaderAwareTrait
{
    /**
     * @var CurrencyLoader|null
     */
    protected ?CurrencyLoader $currencyLoader = null;

    /**
     * @return CurrencyLoader
     */
    protected function getCurrencyLoader(): CurrencyLoader
    {
        if ($this->currencyLoader === null) {
            $this->currencyLoader = CurrencyLoader::new();
        }
        return $this->currencyLoader;
    }

    /**
     * @param CurrencyLoader $currencyLoader
     * @return static
     */
    public function setCurrencyLoader(CurrencyLoader $currencyLoader): static
    {
        $this->currencyLoader = $currencyLoader;
        return $this;
    }
}
