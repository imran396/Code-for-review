<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           May 25, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeAuthAccountRegistration\ThreeD\CallbackResponse\AuthAccountRegistrationSuccess\Handle;

trait AuthAccountRegistrationSuccessCallbackResponseHandlerCreateTrait
{
    /**
     * @var AuthAccountRegistrationSuccessCallbackResponseHandler|null
     */
    protected ?AuthAccountRegistrationSuccessCallbackResponseHandler $authAccountRegistrationSuccessCallbackResponseHandler = null;

    /**
     * @return AuthAccountRegistrationSuccessCallbackResponseHandler
     */
    protected function createAuthAccountRegistrationSuccessCallbackResponseHandler(): AuthAccountRegistrationSuccessCallbackResponseHandler
    {
        return $this->authAccountRegistrationSuccessCallbackResponseHandler ?: AuthAccountRegistrationSuccessCallbackResponseHandler::new();
    }

    /**
     * @param AuthAccountRegistrationSuccessCallbackResponseHandler $handler
     * @return static
     * @internal
     */
    public function setAuthAccountRegistrationSuccessCallbackResponseHandler(AuthAccountRegistrationSuccessCallbackResponseHandler $handler): static
    {
        $this->authAccountRegistrationSuccessCallbackResponseHandler = $handler;
        return $this;
    }
}
