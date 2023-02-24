<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           June 1, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeSettlement\ThreeD\CallbackResponse\SettlementSuccess\Handle\Internal\Build;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Lang\TranslatorAwareTrait;


class NoteBuilder extends CustomizableClass
{
    use TranslatorAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function build(
        int $settlementId,
        string $settlementNote,
        string $threeDStatusResponse,
        string $currencySign,
        float $amount,
        int $systemAccountId,
        ?int $languageId,
    ): string {
        $noteChargeOf = $this->getTranslator()->translate(
            'SETTLEMENTS_CHARGE_OF',
            'mysettlements',
            $systemAccountId,
            $languageId
        );

        if ($threeDStatusResponse === Constants\BillingOpayo::STATUS_ATTEMPTONLY) {
            $settlementNote .= ' 3DSecure=ATTEMPTONLY';
            log_info(
                'Opayo 3DSecure=ATTEMPTONLY for settlement'
                . composeSuffix(['s' => $settlementId])
            );
        }

        $note = $settlementNote . "\n" . $noteChargeOf . " " . $currencySign . $amount;
        return $note;
    }
}
