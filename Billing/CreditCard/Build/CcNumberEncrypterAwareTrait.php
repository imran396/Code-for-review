<?php
/**
 * SAM-5940: Refactor and modularize hashing of cc number to find duplicate cc numbers
 * https://bidpath.atlassian.net/browse/SAM-5940
 *
 * @author        Oleg Kovalyov
 * @since         Mar 31, 2020
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Billing\CreditCard\Build;

trait CcNumberEncrypterAwareTrait
{
    /**
     * @var CcNumberEncrypter|null
     */
    protected ?CcNumberEncrypter $ccNumberEncrypter = null;

    /**
     * @return CcNumberEncrypter
     */
    protected function getCcNumberEncrypter(): CcNumberEncrypter
    {
        if ($this->ccNumberEncrypter === null) {
            $this->ccNumberEncrypter = CcNumberEncrypter::new();
        }
        return $this->ccNumberEncrypter;
    }

    /**
     * @param CcNumberEncrypter $ccNumberEncrypter
     * @return static
     * @internal
     */
    public function setCcNumberEncrypter(CcNumberEncrypter $ccNumberEncrypter): static
    {
        $this->ccNumberEncrypter = $ccNumberEncrypter;
        return $this;
    }
}
