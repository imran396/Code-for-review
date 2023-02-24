<?php
/**
 * SAM-4590: Lot barcode-type custom field generating improvements
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @since           16 Jan, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * @property int AccountId      account.id
 * @property bool Public         true - public side, false - admin side
 */

namespace Sam\CustomField\Base\TextType\Barcode\Validate;

use InvalidArgumentException;
use Sam\Core\Constants\CustomField;
use Sam\Core\Service\CustomizableClass;

/**
 * Class BarcodeValidator
 * @package Sam\CustomField\Base\TextType\Barcode
 */
class BarcodeValidator extends CustomizableClass
{
    private const ERR_REQUIRED_LENGTH = 1;
    private const ERR_NOT_NUMERIC = 2;
    private const ERR_NOT_ALPHANUMERIC = 3;
    private const ERR_UNKNOWN_TYPE = 4;

    protected ?int $error = null;
    /**
     * @var string[]
     */
    protected array $errorMessages = [
        self::ERR_REQUIRED_LENGTH => 'Required length is ',
        self::ERR_NOT_NUMERIC => 'Must be numeric',
        self::ERR_NOT_ALPHANUMERIC => 'Barcode contains invalid character(s)',
        self::ERR_UNKNOWN_TYPE => 'Invalid Barcode',
    ];

    protected ?string $barcode = null;
    protected ?int $type = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $barcode
     * @return static
     */
    public function setBarcode(string $barcode): static
    {
        $this->barcode = trim($barcode);
        return $this;
    }

    /**
     * @return string
     */
    public function getBarcode(): string
    {
        if ($this->barcode === null) {
            throw new InvalidArgumentException("Barcode not defined");
        }
        return $this->barcode;
    }

    /**
     * @param int $type
     * @return static
     */
    public function setType(int $type): static
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        if (!$this->type) {
            throw new InvalidArgumentException("Type not defined");
        }
        return $this->type;
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        // If length not meet, then validation failed
        if (!$this->validateRequiredLength()) {
            return false;
        }

        // If not numeric and not alphanumeric, then validation failed
        if (
            !$this->validateIsNumericType()
            && !$this->validateIsAlphanumericType()
        ) {
            $this->addError(self::ERR_UNKNOWN_TYPE);
            return false;
        }

        //if is numeric or is alphanumeric, then validation passed
        if (
            $this->validateIsNumericType()
            || $this->validateIsAlphanumericType()
        ) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function validateRequiredLength(): bool
    {
        $type = $this->getType();
        if (
            array_key_exists($type, CustomField::$barcodeLength)
            && strlen($this->getBarcode()) !== CustomField::$barcodeLength[$type]
        ) {
            $this->addError(self::ERR_REQUIRED_LENGTH);
            $this->errorMessages[self::ERR_REQUIRED_LENGTH] .= ' ' . CustomField::$barcodeLength[$type];
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    protected function validateIsNumericType(): bool
    {
        $type = $this->getType();
        if (in_array($type, CustomField::$barcodeNumericTypes, true)) {
            if (preg_match('/^\d+$/', $this->getBarcode())) {
                return true;
            }

            $this->addError(self::ERR_NOT_NUMERIC);
        }
        return false;
    }

    /**
     * @return bool
     */
    protected function validateIsAlphanumericType(): bool
    {
        $type = $this->getType();
        if (in_array($type, CustomField::$barcodeAlphanumericTypes, true)) {
            if (preg_match('/^[0-9A-Z\-*+\$%\/. ]+$/', $this->getBarcode())) {
                return true;
            }

            $this->addError(self::ERR_NOT_ALPHANUMERIC);
        }
        return false;
    }

    /**
     * @param int $error
     * @return void
     */
    protected function addError(int $error): void
    {
        $this->error = $error;
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return $this->error !== null;
    }

    /**
     * @return int
     */
    public function getError(): int
    {
        return $this->error;
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessages[$this->error];
    }
}
