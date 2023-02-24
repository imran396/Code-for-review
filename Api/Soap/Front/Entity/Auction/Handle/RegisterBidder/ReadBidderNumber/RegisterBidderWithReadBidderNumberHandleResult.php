<?php
/**
 *
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\Soap\Front\Entity\Auction\Handle\RegisterBidder\ReadBidderNumber;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class RegisterBidderWithReadBidderNumberHandleResult
 * @package Sam\Api\Soap\Front\Entity\Auction\Handle\RegisterBidder\ReadBidderNumber
 */
class RegisterBidderWithReadBidderNumberHandleResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_NOT_REGISTERED = 1;
    public const ERR_NOT_APPROVED = 2;
    public const OK_FOUND = 11;

    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::ERR_NOT_REGISTERED => 'User not registered in auction',
        self::ERR_NOT_APPROVED => 'User not approved in auction',
    ];

    /** @var string[] */
    protected const SUCCESS_MESSAGES = [
        self::OK_FOUND => 'Bidder number %s successfully found',
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

    // --- Mutation ---

    public function addSuccess(int $code, ?string $message = null): static
    {
        $this->getResultStatusCollector()->addSuccess($code, $message);
        return $this;
    }

    public function addSuccessWithInjectedInMessageArguments(int $code, array $arguments = []): static
    {
        $this->getResultStatusCollector()->addSuccessWithInjectedInMessageArguments($code, $arguments);
        return $this;
    }

    public function addError(int $code, ?string $message = null): static
    {
        $this->getResultStatusCollector()->addError($code, $message);
        return $this;
    }

    public function setBidderNumber(string $bidderNumber): static
    {
        $this->bidderNumber = $bidderNumber;
        return $this;
    }

    // --- Query methods ---

    public function hasSuccess(): bool
    {
        return $this->getResultStatusCollector()->hasSuccess();
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage("\n");
    }

    public function successMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedSuccessMessage("\n");
    }

    public function statusCode(): ?int
    {
        return $this->getResultStatusCollector()->getFirstStatusCode();
    }
}
