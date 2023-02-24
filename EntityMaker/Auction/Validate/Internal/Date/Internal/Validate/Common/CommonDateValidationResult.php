<?php
/**
 * SAM-10450: Decouple auction date validation logic into internal services
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 25, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Common;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\ErrorContainerInterface;

/**
 * Class CommonDateValidationResult
 * @package Sam\EntityMaker\Auction\Validate\Internal\Date\Internal\Validate\Common
 */
class CommonDateValidationResult extends CustomizableClass implements ErrorContainerInterface
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_PUBLISH_DATE_INVALID = 1;
    public const ERR_UNPUBLISH_DATE_INVALID = 2;
    public const ERR_START_REGISTER_DATE_INVALID = 3;
    public const ERR_END_REGISTER_DATE_INVALID = 4;
    public const ERR_END_REGISTER_DATE_EARLIER_START_REGISTER_DATE = 5;
    public const ERR_START_BIDDING_DATE_INVALID = 6;


    public const ERROR_MESSAGES = [
        self::ERR_PUBLISH_DATE_INVALID => 'Invalid',
        self::ERR_UNPUBLISH_DATE_INVALID => 'Invalid',
        self::ERR_START_REGISTER_DATE_INVALID => 'Invalid',
        self::ERR_END_REGISTER_DATE_INVALID => 'Invalid',
        self::ERR_START_BIDDING_DATE_INVALID => 'Invalid',
        self::ERR_END_REGISTER_DATE_EARLIER_START_REGISTER_DATE => 'Should be later than Start register date',
    ];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $errorMessages
     * @param string|null $messageGlue null means default value of ResultStatusCollector.
     * @return $this
     */
    public function construct(
        array $errorMessages = [],
        ?string $messageGlue = null
    ): static {
        $errorMessages = $errorMessages ?: self::ERROR_MESSAGES;
        $this->getResultStatusCollector()
            ->construct($errorMessages)
            ->setResultMessageGlue($messageGlue);
        return $this;
    }

    public function addError(int $code, ?string $message = null): static
    {
        if (!$this->hasErrorByCode($code)) {
            $this->getResultStatusCollector()->addError($code, $message);
        }
        return $this;
    }

    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function hasErrorByCode(int $code): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError($code);
    }

    public function hasSuccess(): bool
    {
        return !$this->hasError();
    }

    public function logData(): array
    {
        $logData = [];
        if ($this->hasError()) {
            $errorMessages = [];
            foreach ($this->getResultStatusCollector()->getErrorStatuses() as $errorStatus) {
                $errorMessages[] = sprintf('%s (%d)', $errorStatus->getMessage(), $errorStatus->getCode());
            }
            $logData['error messages'] = implode(', ', $errorMessages);
        }
        return $logData;
    }
}
