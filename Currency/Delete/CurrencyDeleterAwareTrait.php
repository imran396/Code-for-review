<?php
/**
 *
 * SAM-4722: Currency deleter
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2018-12-20
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Currency\Delete;

/**
 * Trait CurrencyDeleterAwareTrait
 * @package Sam\Currency\Delete
 */
trait CurrencyDeleterAwareTrait
{
    /**
     * @var CurrencyDeleter|null
     */
    protected ?CurrencyDeleter $currencyDeleter = null;

    /**
     * @return CurrencyDeleter
     */
    protected function getCurrencyDeleter(): CurrencyDeleter
    {
        if ($this->currencyDeleter === null) {
            $this->currencyDeleter = CurrencyDeleter::new();
        }
        return $this->currencyDeleter;
    }

    /**
     * @param CurrencyDeleter $currencyDeleter
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setCurrencyDeleter(CurrencyDeleter $currencyDeleter): static
    {
        $this->currencyDeleter = $currencyDeleter;
        return $this;
    }
}
