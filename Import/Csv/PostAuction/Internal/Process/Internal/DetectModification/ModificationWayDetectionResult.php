<?php
/**
 * SAM-9360: Refactor \Lot_PostCsvUpload
 * SAM-4832: Post auction import-Issue when no winning information in csv cell
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\PostAuction\Internal\Process\Internal\DetectModification;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class ModificationWayDetectionResult
 */
class ModificationWayDetectionResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const OK_HP_ABSENT_IN_INPUT_AND_WB_ABSENT_IN_INPUT_AND_UU_DISABLED_BUT_HP_OR_WB_PRESENT_IN_DB = 1;
    public const OK_HP_ABSENT_IN_INPUT_AND_WB_ABSENT_IN_INPUT_AND_UU_DISABLED_BUT_HP_AND_WB_ABSENT_IN_DB = 2;
    public const OK_HP_PRESENT_IN_INPUT_AND_WB_PRESENT_IN_INPUT = 3;
    public const OK_HP_ABSENT_IN_INPUT_AND_WB_ABSENT_IN_INPUT_AND_UU_ENABLED = 4;
    public const OK_HP_PRESENT_IN_INPUT_AND_WB_ABSENT_IN_INPUT = 5;

    public const ERR_HP_ABSENT_IN_INPUT_AND_WB_PRESENT_IN_INPUT = 11;

    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::ERR_HP_ABSENT_IN_INPUT_AND_WB_PRESENT_IN_INPUT => 'Hammer price must present in input when winning bidder email is set',
    ];

    /** @var string[] */
    protected const SUCCESS_MESSAGES = [
        self::OK_HP_ABSENT_IN_INPUT_AND_WB_ABSENT_IN_INPUT_AND_UU_DISABLED_BUT_HP_OR_WB_PRESENT_IN_DB => 'Hammer price and winning bidder absent in input, and "Unassign unsold lots" disable, '
            . 'since hammer price or winning bidder is defined in DB, then lot state should be kept untouched',
        self::OK_HP_ABSENT_IN_INPUT_AND_WB_ABSENT_IN_INPUT_AND_UU_DISABLED_BUT_HP_AND_WB_ABSENT_IN_DB => 'Hammer price and winning bidder absent in input, and "Unassign unsold lots" disable, '
            . 'since hammer price and winning bidder both are not defined in DB, then lot status should be unsold',
        self::OK_HP_PRESENT_IN_INPUT_AND_WB_PRESENT_IN_INPUT => 'Hammer price and winning bidder are defined by input, lot must be sold',
        self::OK_HP_ABSENT_IN_INPUT_AND_WB_ABSENT_IN_INPUT_AND_UU_ENABLED => 'Hammer price and winning bidder absent in input, since "Unassign unsold lots" enabled, '
            . 'then lot must be unassigned and winning info wiped out',
        self::OK_HP_PRESENT_IN_INPUT_AND_WB_ABSENT_IN_INPUT => 'Hammer price is defined in input, but winning bidder is not, lot must be sold without winner', // TODO: clarify ?
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
        $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES, self::SUCCESS_MESSAGES);
        return $this;
    }


    // --- Mutate state ---

    public function addError(int $code): static
    {
        $this->getResultStatusCollector()->addError($code);
        return $this;
    }

    public function addSuccess(int $code): static
    {
        $this->getResultStatusCollector()->addSuccess($code);
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

    /**
     * @return int[]
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    /**
     * @return int[]
     */
    public function successCodes(): array
    {
        return $this->getResultStatusCollector()->getSuccessCodes();
    }

    public function statusCode(): ?int
    {
        if ($this->hasError()) {
            return $this->getResultStatusCollector()->getFirstErrorCode();
        }
        if ($this->hasSuccess()) {
            return $this->getResultStatusCollector()->getFirstSuccessCode();
        }
        return null;
    }

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

    public function errorMessage(string $glue = ', '): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage($glue);
    }

    /**
     * @return string[]
     */
    public function errorMessages(): array
    {
        return $this->getResultStatusCollector()->getErrorMessages();
    }

    /**
     * @param string $glue
     * @return string
     */
    public function successMessage(string $glue = ', '): string
    {
        return $this->getResultStatusCollector()->getConcatenatedSuccessMessage($glue);
    }

    public function shouldNotTouch(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteSuccess(
            [
                self::OK_HP_ABSENT_IN_INPUT_AND_WB_ABSENT_IN_INPUT_AND_UU_DISABLED_BUT_HP_OR_WB_PRESENT_IN_DB
            ]
        );
    }

    public function shouldUnsell(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteSuccess(
            [
                self::OK_HP_ABSENT_IN_INPUT_AND_WB_ABSENT_IN_INPUT_AND_UU_DISABLED_BUT_HP_AND_WB_ABSENT_IN_DB
            ]
        );
    }

    public function shouldSellWithWinner(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteSuccess(
            [
                self::OK_HP_PRESENT_IN_INPUT_AND_WB_PRESENT_IN_INPUT,
            ]
        );
    }

    public function shouldUnsellAndUnassign(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteSuccess(
            [
                self::OK_HP_ABSENT_IN_INPUT_AND_WB_ABSENT_IN_INPUT_AND_UU_ENABLED,
            ]
        );
    }

    public function shouldSellWithoutWinner(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteSuccess(
            [
                self::OK_HP_PRESENT_IN_INPUT_AND_WB_ABSENT_IN_INPUT,
            ]
        );
    }

    public function shouldSell(): bool
    {
        return $this->shouldSellWithoutWinner()
            || $this->shouldSellWithWinner();
    }

}
