<?php
/**
 * SAM-5664: Extract update action from Auction Sms page at admin side
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 03, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionSmsForm\Save;

use Auction;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\WriteRepository\Entity\Auction\AuctionWriteRepositoryAwareTrait;

/**
 * Class AuctionSmsEditor
 * @package Sam\View\Admin\Form\AuctionSmsForm\Save
 * @method Auction getAuction() - we guarantee entity existence by caller
 */
class AuctionSmsEditor extends CustomizableClass
{
    use AuctionAwareTrait;
    use AuctionWriteRepositoryAwareTrait;
    use EditorUserAwareTrait;
    use ResultStatusCollectorAwareTrait;

    public const ERR_LOTS_IN_ADVANCE_INVALID = 1;
    public const ERR_MINUTES_BEFORE_INVALID = 2;
    public const OK_SAVED = 1;

    /**
     * General error message basically rendered on the top
     */
    private string $generalErrorMessage = 'Please see below for missing/false entries';
    private ?string $eventId = null;
    private ?string $textMsgNotification = null;
    private ?string $minutesBefore = null;
    private int|string|null $lotsInAdvance = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $errorMessages = [
            self::ERR_LOTS_IN_ADVANCE_INVALID => 'Invalid',
            self::ERR_MINUTES_BEFORE_INVALID => 'Invalid',
        ];
        $successMessages = [
            self::OK_SAVED => 'Your changes have been saved!',
        ];
        $this->getResultStatusCollector()->construct($errorMessages, $successMessages);
        return $this;
    }

    public function update(): void
    {
        $auction = $this->getAuction();
        $auction->EventId = $this->getEventId();
        $auction->TextMsgNotification = $this->getTextMsgNotification();

        if ($this->isAuctionTypeEqual(Constants\Auction::TIMED)) {
            $auction->NotifyXMinutes = Cast::toInt($this->getMinutesBefore());
        } else {
            $auction->NotifyXLots = $this->getLotsInAdvance();
        }
        $this->getAuctionWriteRepository()->saveWithModifier($auction, $this->getEditorUserId());
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        $this->validateLotsInAdvance();
        $this->validateMinutesBefore();
        $isValid = !$this->getResultStatusCollector()->hasError();
        return $isValid;
    }

    /**
     * @return bool
     */
    private function validateMinutesBefore(): bool
    {
        $collector = $this->getResultStatusCollector();
        $minutesBefore = $this->getMinutesBefore();
        if (!$this->isEmptyValue($minutesBefore) && !NumberValidator::new()->isInt($minutesBefore)) {
            $collector->addError(self::ERR_MINUTES_BEFORE_INVALID);
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    private function validateLotsInAdvance(): bool
    {
        $collector = $this->getResultStatusCollector();
        $lotInAdvance = $this->getLotsInAdvance();
        if (
            !$this->isEmptyValue($lotInAdvance)
            && $this->isAuctionTypeEqual(Constants\Auction::RTB_AUCTION_TYPES)
            && !NumberValidator::new()->isInt($lotInAdvance)
        ) {
            $collector->addError(self::ERR_LOTS_IN_ADVANCE_INVALID);
            return false;
        }
        return true;
    }

    /**
     * @param $types
     * @return bool
     */
    private function isAuctionTypeEqual($types): bool
    {
        if (!is_array($types)) {
            $types = [$types];
        }

        return in_array($this->getAuction()->AuctionType, $types, true);
    }

    /**
     * @param $value
     * @return bool
     */
    private function isEmptyValue($value): bool
    {
        return (string)$value === '';
    }

    /**
     * @return string
     */
    public function getEventId(): ?string
    {
        return $this->eventId;
    }

    /**
     * @param string $eventId
     * @return static
     */
    public function setEventId(string $eventId): static
    {
        $this->eventId = $eventId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTextMsgNotification(): ?string
    {
        return $this->textMsgNotification;
    }

    /**
     * @param string $textMsgNotification
     * @return AuctionSmsEditor
     */
    public function setTextMsgNotification(string $textMsgNotification): AuctionSmsEditor
    {
        $this->textMsgNotification = trim($textMsgNotification);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMinutesBefore(): ?string
    {
        return $this->minutesBefore;
    }

    /**
     * @param string $minutesBefore
     * @return static
     */
    public function setMinutesBefore(string $minutesBefore): static
    {
        $this->minutesBefore = $minutesBefore;
        return $this;
    }

    /**
     * @return int|string|null
     */
    public function getLotsInAdvance(): int|string|null
    {
        return $this->lotsInAdvance;
    }

    /**
     * @param int|string $lotsInAdvance
     * @return static
     */
    public function setLotsInAdvance(int|string $lotsInAdvance): static
    {
        $this->lotsInAdvance = $lotsInAdvance;
        return $this;
    }

    /**
     * @return string
     */
    public function lotsInAdvanceErrorMessage(): string
    {
        return (string)$this->getResultStatusCollector()
            ->findFirstErrorMessageAmongCodes([static::ERR_LOTS_IN_ADVANCE_INVALID]);
    }

    /**
     * @return string
     */
    public function minutesBeforeErrorMessage(): string
    {
        return (string)$this->getResultStatusCollector()
            ->findFirstErrorMessageAmongCodes([static::ERR_MINUTES_BEFORE_INVALID]);
    }

    /**
     * @return string
     */
    public function generalErrorMessage(): string
    {
        return $this->generalErrorMessage;
    }

    /**
     * @return bool
     */
    public function hasLotsInAdvanceError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError([static::ERR_LOTS_IN_ADVANCE_INVALID]);
    }

    /**
     * @return bool
     */
    public function hasMinutesBeforeError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError([static::ERR_MINUTES_BEFORE_INVALID]);
    }

    /**
     * @return string
     */
    public function successMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedSuccessMessage();
    }
}
