<?php
/**
 * SAM-9530: "User Absentee Bid" page - extract existing bid detection logic
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionBidderAbsenteeForm\Validate\AbsenteeBidExistence;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AbsenteeBidExistenceCheckingResult
 * @package
 */
class AbsenteeBidExistenceCheckingResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_AUCTION_ID_UNDEFINED = 1;
    public const ERR_USER_ID_UNDEFINED = 2;
    public const ERR_LOT_NO_INCORRECT = 3;
    public const ERR_LOT_NOT_FOUND_BY_LOT_NO = 4;

    public const OK_ABSENTEE_BID_FOUND = 11;

    public const INFO_ABSENTEE_BID_NOT_FOUND = 21;

    protected const ERROR_MESSAGES = [
        self::ERR_AUCTION_ID_UNDEFINED => 'Auction id undefined',
        self::ERR_USER_ID_UNDEFINED => 'User id undefined',
        self::ERR_LOT_NO_INCORRECT => 'Lot# incorrect',
        self::ERR_LOT_NOT_FOUND_BY_LOT_NO => 'Lot item not found by lot#',
    ];

    protected const SUCCESS_MESSAGES = [
        self::OK_ABSENTEE_BID_FOUND => 'Absentee bid found for user and lot',
    ];

    protected const INFO_MESSAGES = [
        self::INFO_ABSENTEE_BID_NOT_FOUND => 'Absentee bid not found for user and lot',
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
     * @return $this
     */
    public function construct(): static
    {
        $this->getResultStatusCollector()->construct(
            self::ERROR_MESSAGES,
            self::SUCCESS_MESSAGES,
            [],
            self::INFO_MESSAGES
        );
        return $this;
    }

    // --- Mutate state ---

    public function addError(int $code): static
    {
        $this->getResultStatusCollector()->addError($code);
        return $this;
    }

    public function addErrorWithAppendedMessage(int $code, string $append): static
    {
        $this->getResultStatusCollector()->addErrorWithAppendedMessage($code, $append);
        return $this;
    }

    public function addSuccess(int $code): static
    {
        $this->getResultStatusCollector()->addSuccess($code);
        return $this;
    }

    public function addInfo(int $code): static
    {
        $this->getResultStatusCollector()->addInfo($code);
        return $this;
    }

    // --- Query state ---

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function hasSuccess(): bool
    {
        return $this->getResultStatusCollector()->hasSuccess();
    }

    public function hasInfo(): bool
    {
        return $this->getResultStatusCollector()->hasInfo();
    }

    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
    }

    public function statusCode(): ?int
    {
        if ($this->hasError()) {
            return $this->getResultStatusCollector()->getFirstErrorCode();
        }
        if ($this->hasSuccess()) {
            return $this->getResultStatusCollector()->getFirstSuccessCode();
        }
        if ($this->hasInfo()) {
            return $this->getResultStatusCollector()->getFirstInfoCode();
        }
        return null;
    }
}
