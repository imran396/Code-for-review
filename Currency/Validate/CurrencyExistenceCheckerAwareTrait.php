<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/19/18
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Currency\Validate;

/**
 * Trait CurrencyExistenceCheckerAwareTrait
 * @package Sam\Currency\Validate
 */
trait CurrencyExistenceCheckerAwareTrait
{
    /**
     * @var CurrencyExistenceChecker|null
     */
    protected ?CurrencyExistenceChecker $currencyExistenceChecker = null;

    /**
     * @return CurrencyExistenceChecker
     */
    protected function getCurrencyExistenceChecker(): CurrencyExistenceChecker
    {
        if ($this->currencyExistenceChecker === null) {
            $this->currencyExistenceChecker = CurrencyExistenceChecker::new();
        }
        return $this->currencyExistenceChecker;
    }

    /**
     * @param CurrencyExistenceChecker $currencyExistenceChecker
     * @return static
     * @internal
     */
    public function setCurrencyExistenceChecker(CurrencyExistenceChecker $currencyExistenceChecker): static
    {
        $this->currencyExistenceChecker = $currencyExistenceChecker;
        return $this;
    }
}
