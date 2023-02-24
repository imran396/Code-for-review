<?php
/**
 * SAM-10335: Allow to adjust CC surcharge per account: Implementation (Dev)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SystemParameterPaymentForm\CreditCard\Edit\Save;

use CreditCard;
use CreditCardSurcharge;
use Sam\Storage\WriteRepository\Entity\CreditCard\CreditCardWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\CreditCardSurcharge\CreditCardSurchargeWriteRepositoryAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\View\Admin\Form\SystemParameterPaymentForm\CreditCard\Edit\Common\CreditCardEditingInput as Input;
use Sam\View\Admin\Form\SystemParameterPaymentForm\CreditCard\Edit\Save\Internal\Load\DataProviderCreateTrait;
use Sam\Core\Service\CustomizableClass;


class CreditCardEditingSaver extends CustomizableClass
{
    use CreditCardWriteRepositoryAwareTrait;
    use CreditCardSurchargeWriteRepositoryAwareTrait;
    use DataProviderCreateTrait;
    use NumberFormatterAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function saveForMainAccount(Input $input): CreditCard
    {
        $dataProvider = $this->createDataProvider();
        $creditCard = $dataProvider->loadCreditCard($input->creditCardId, true);
        if (!$creditCard) {
            $creditCard = $dataProvider->newCreditCard();
        }
        $creditCard->Active = true;
        $creditCard->Name = $input->name;
        $creditCard->Surcharge = $input->surcharge === ''
            ? null
            : $this->getNumberFormatter()->parsePercent($input->surcharge, $input->systemAccountId);
        $this->getCreditCardWriteRepository()->saveWithModifier($creditCard, $input->editorUserId);
        return $creditCard;
    }

    public function saveForPortalAccount(Input $input): CreditCardSurcharge
    {
        $dataProvider = $this->createDataProvider();
        $creditCardSurcharge = $dataProvider->loadCreditCardSurcharge($input->creditCardId, $input->systemAccountId, true);
        if (!$creditCardSurcharge) {
            $creditCardSurcharge = $dataProvider->newCreditCardSurcharge();
            $creditCardSurcharge->AccountId = $input->systemAccountId;
            $creditCardSurcharge->CreditCardId = $input->creditCardId;
        }
        $creditCardSurcharge->Percent = $input->surcharge === ''
            ? null
            : $this->getNumberFormatter()->parsePercent($input->surcharge, $input->systemAccountId);
        $this->getCreditCardSurchargeWriteRepository()->saveWithModifier($creditCardSurcharge, $input->editorUserId);
        return $creditCardSurcharge;
    }
}
