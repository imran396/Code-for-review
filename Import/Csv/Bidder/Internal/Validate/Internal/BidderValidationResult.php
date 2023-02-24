<?php
/**
 * SAM-3796: Bidder upload into auction
 * SAM-9366: Refactor Sam\Bidder\AuctionBidder\CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 06, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Bidder\Internal\Validate\Internal;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Container for bidder info validation errors
 *
 * Class BidderValidationResult
 * @package Sam\Import\Csv\Bidder\Internal\Validate\Internal
 */
class BidderValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_EMAIL_REQUIRED = 1;
    public const ERR_BIDDER_NO_INVALID = 2;
    public const ERR_BIDDER_NO_INVALID_ENCODING = 3;
    public const ERR_BIDDER_NO_EXIST = 4;

    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::ERR_EMAIL_REQUIRED => 'Email required',
        self::ERR_BIDDER_NO_INVALID => 'Invalid bidder no',
        self::ERR_BIDDER_NO_INVALID_ENCODING => 'Invalid bidder no encoding',
        self::ERR_BIDDER_NO_EXIST => 'Bidder no exist',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(): static
    {
        $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES);
        return $this;
    }

    public function addError(int $errorCode): static
    {
        $this->getResultStatusCollector()->addError($errorCode);
        return $this;
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function getErrorMessages(): array
    {
        return $this->getResultStatusCollector()->getErrorMessages();
    }

    public function getErrorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    public function getErrorStatuses(): array
    {
        return $this->getResultStatusCollector()->getErrorStatuses();
    }
}
