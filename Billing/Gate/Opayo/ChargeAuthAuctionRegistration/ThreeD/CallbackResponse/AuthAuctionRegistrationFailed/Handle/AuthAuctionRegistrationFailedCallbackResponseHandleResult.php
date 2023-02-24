<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           May 24, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeAuthAuctionRegistration\ThreeD\CallbackResponse\AuthAuctionRegistrationFailed\Handle;

use AuctionBidder;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;


class AuthAuctionRegistrationFailedCallbackResponseHandleResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_REGISTRATION_ERROR = 1;

    public const OK_SUCCESS = 11;


    private readonly bool $isResponsive;
    private readonly string $message;
    private readonly string $redirectUrl;
    private readonly ?AuctionBidder $auctionBidder;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        bool $isResponsive,
        string $message,
        string $redirectUrl,
        ?AuctionBidder $auctionBidder
    ): static {
        $this->getResultStatusCollector()->construct(
            [
                self::ERR_REGISTRATION_ERROR => 'Failed to handle bidder registration after failed 3d secure'
            ],
            [
                self::OK_SUCCESS => 'Successfully handled failed auction registration callback'
            ]
        );
        $this->isResponsive = $isResponsive;
        $this->message = $message;
        $this->redirectUrl = $redirectUrl;
        $this->auctionBidder = $auctionBidder;
        return $this;
    }

    // --- Mutate ---

    public function addError(int $code, ?string $message = null): static
    {
        $this->getResultStatusCollector()->addError($code, $message);
        return $this;
    }

    public function addSuccess(int $code, ?string $message = null): static
    {
        $this->getResultStatusCollector()->addSuccess($code, $message);
        return $this;
    }

    // --- Query ---
    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function successMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedSuccessMessage();
    }

    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
    }

    public function statusCode(): ?int
    {
        if ($this->getResultStatusCollector()->hasSuccess()) {
            return $this->getResultStatusCollector()->getFirstSuccessCode();
        }
        if ($this->hasError()) {
            return $this->getResultStatusCollector()->getFirstErrorCode();
        }
        return null;
    }

    public function isResponsive(): bool
    {
        return $this->isResponsive;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getRedirectUrl(): string
    {
        return $this->redirectUrl;
    }

    public function getAuctionBidder(): ?AuctionBidder
    {
        return $this->auctionBidder;
    }
}
