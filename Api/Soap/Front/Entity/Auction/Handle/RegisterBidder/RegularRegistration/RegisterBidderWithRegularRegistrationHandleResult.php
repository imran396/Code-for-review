<?php
/**
 * SAM-5041: Soap API RegisterBidder improvement
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 13, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\Soap\Front\Entity\Auction\Handle\RegisterBidder\RegularRegistration;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class RegisterBidderRegularWayHandleResult
 * @package Sam\Api\Soap\Front\Entity\Auction\Handle\RegisterBidder\RegularAuctionBidderRegistration
 */
class RegisterBidderWithRegularRegistrationHandleResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_REGISTRATION_FAILED = 1;

    public const OK_REGISTERED = 11;
    public const OK_APPROVED = 12;

    protected const ERROR_MESSAGES = [
        self::ERR_REGISTRATION_FAILED => 'Auction bidder registration failed', // must overwrite
    ];

    protected const SUCCESS_MESSAGES = [
        self::OK_REGISTERED => 'User registered in auction',
        self::OK_APPROVED => 'User approved in auction with bidder number %s',
    ];

    public string $bidderNumber = '';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return $this
     */
    public function construct(): static
    {
        $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES, self::SUCCESS_MESSAGES);
        return $this;
    }

    // --- Mutation methods ---

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

    public function addSuccessWithInjectedInMessageArguments(int $code, array $injectArgs = []): static
    {
        $this->getResultStatusCollector()->addSuccessWithInjectedInMessageArguments($code, $injectArgs);
        return $this;
    }

    public function setBidderNumber(string $bidderNumber): static
    {
        $this->bidderNumber = $bidderNumber;
        return $this;
    }

    // --- Query methods ---

    public function statusMessage(): string
    {
        if ($this->hasError()) {
            return $this->errorMessage();
        }

        if ($this->hasSuccess()) {
            return $this->successMessage();
        }

        return '';
    }

    public function statusCode(): ?int
    {
        return $this->getResultStatusCollector()->getFirstStatusCode();
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function hasSuccess(): bool
    {
        return $this->getResultStatusCollector()->hasSuccess();
    }

    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage("\n");
    }

    public function successMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedSuccessMessage("\n");
    }
}
