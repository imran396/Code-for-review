<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           Oct 3, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeAuthAccountRegistration\ThreeD\CallbackResponse\AuthAccountRegistrationFailed;

use Sam\Core\Service\CustomizableClass;

/**
 * Class AuthAccountRegistrationFailedCallbackResponseHandlingInput
 * @package Sam\Billing\Gate\Opayo\ChargeAuthAccountRegistration\ThreeD\CallbackResponse\AuthAccountRegistrationFailed
 */
class AuthAccountRegistrationFailedCallbackResponseHandlingInput extends CustomizableClass
{
    public readonly string $threeDStatusResponse;
    public readonly string $cardCodeResponse;
    public readonly int $systemAccountId;
    public readonly ?int $languageId;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $threeDStatusResponse
     * @param string $cardCodeResponse
     * @param int $systemAccountId
     * @param int|null $languageId
     * @return $this
     */
    public function construct(
        string $threeDStatusResponse,
        string $cardCodeResponse,
        int $systemAccountId,
        ?int $languageId
    ): static {
        $this->threeDStatusResponse = $threeDStatusResponse;
        $this->cardCodeResponse = $cardCodeResponse;
        $this->systemAccountId = $systemAccountId;
        $this->languageId = $languageId;
        return $this;
    }
}
