<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           May 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargePublicInvoiceView\ThreeD\CallbackResponse\PublicInvoiceViewSuccess\Handle\Internal\Build;

use Sam\Billing\Gate\Opayo\ChargePublicInvoiceView\ThreeD\CallbackResponse\PublicInvoiceViewSuccess\Handle\Internal\Build\Internal\Load\DataProviderCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Lang\TranslatorAwareTrait;

class MessageBuilder extends CustomizableClass
{
    use DataProviderCreateTrait;
    use TranslatorAwareTrait;


    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function build(
        int $accountId,
        int $userId,
        int $systemAccountId,
        ?int $languageId,
        bool $isReadOnlyDb = false,
    ): string {
        $translator = $this->getTranslator();
        $langSuccess = $translator->translate(
            'MYINVOICES_SUCCESS_PAYMENT',
            'myinvoices',
            $systemAccountId,
            $languageId
        );

        $dataProvider = $this->createDataProvider();
        $supportEmail = $dataProvider->loadSupportEmail($accountId);
        $userInfo = $dataProvider->loadUserInfoOrCreate($userId, $isReadOnlyDb);
        return sprintf(
            $langSuccess,
            $userInfo->FirstName,
            $supportEmail
        );
    }
}
