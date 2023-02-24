<?php
/**
 * SAM-10915: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract Authorize.Net invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 1, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\AuthorizeNet\Internal\InputCcCharge;

trait InputCcChargerCreateTrait
{
    /**
     * @var InputCcCharger|null
     */
    protected ?InputCcCharger $inputCcCharger = null;

    /**
     * @return InputCcCharger
     */
    protected function createInputCcCharger(): InputCcCharger
    {
        return $this->inputCcCharger ?: InputCcCharger::new();
    }

    /**
     * @param InputCcCharger $inputCcCharger
     * @return $this
     * @internal
     */
    public function setInputCcCharger(InputCcCharger $inputCcCharger): static
    {
        $this->inputCcCharger = $inputCcCharger;
        return $this;
    }
}
