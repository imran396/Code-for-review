<?php
/**
 * Credit Cards Data Loader Create Trait
 *
 * SAM-6442: Refactor system parameters invoicing and payment page at admin side
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 25, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SystemParameterPaymentForm\Load;


/**
 * Trait CreditCardsDataLoaderCreateTrait
 */
trait CreditCardsDataLoaderCreateTrait
{
    protected ?CreditCardsDataLoader $creditCardsDataLoader = null;

    /**
     * @return CreditCardsDataLoader
     */
    protected function createCreditCardsDataLoader(): CreditCardsDataLoader
    {
        return $this->creditCardsDataLoader ?: CreditCardsDataLoader::new();
    }

    /**
     * @param CreditCardsDataLoader $creditCardsDataLoader
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setCreditCardsDataLoader(CreditCardsDataLoader $creditCardsDataLoader): static
    {
        $this->creditCardsDataLoader = $creditCardsDataLoader;
        return $this;
    }
}
