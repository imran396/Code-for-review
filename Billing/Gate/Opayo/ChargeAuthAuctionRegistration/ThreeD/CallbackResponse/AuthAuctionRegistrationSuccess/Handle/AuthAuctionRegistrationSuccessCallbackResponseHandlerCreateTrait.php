<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           May 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeAuthAuctionRegistration\ThreeD\CallbackResponse\AuthAuctionRegistrationSuccess\Handle;

trait AuthAuctionRegistrationSuccessCallbackResponseHandlerCreateTrait
{
    /**
     * @var AuthAuctionRegistrationSuccessCallbackResponseHandler|null
     */
    protected ?AuthAuctionRegistrationSuccessCallbackResponseHandler $authAuctionRegistrationSuccessCallbackResponseHandler = null;

    /**
     * @return AuthAuctionRegistrationSuccessCallbackResponseHandler
     */
    protected function createAuthAuctionRegistrationSuccessCallbackResponseHandler(): AuthAuctionRegistrationSuccessCallbackResponseHandler
    {
        return $this->authAuctionRegistrationSuccessCallbackResponseHandler ?: AuthAuctionRegistrationSuccessCallbackResponseHandler::new();
    }

    /**
     * @param AuthAuctionRegistrationSuccessCallbackResponseHandler $handler
     * @return static
     * @internal
     */
    public function setAuthAuctionRegistrationSuccessCallbackResponseHandler(AuthAuctionRegistrationSuccessCallbackResponseHandler $handler): static
    {
        $this->authAuctionRegistrationSuccessCallbackResponseHandler = $handler;
        return $this;
    }
}
