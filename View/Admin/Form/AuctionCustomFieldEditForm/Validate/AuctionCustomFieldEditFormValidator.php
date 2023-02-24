<?php
/**
 * SAM-6585: Refactor auction custom field management to separate module
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Ivan Zgoniaiko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan. 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionCustomFieldEditForm\Validate;

use Sam\Core\Constants;
use Sam\Core\Data\Normalize\NormalizerAwareTrait;
use Sam\Core\Data\Normalize\NormalizerInterface;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\CustomField\Base\Help\BaseCustomFieldHelperAwareTrait;
use Sam\View\Admin\Form\AuctionCustomFieldEditForm\Dto\AuctionCustomFieldEditFormDto;
use Sam\View\Admin\Form\AuctionCustomFieldEditForm\Load\AuctionCustomFieldEditFormDataProviderAwareTrait;

class AuctionCustomFieldEditFormValidator extends CustomizableClass
{
    use AuctionCustomFieldEditFormDataProviderAwareTrait;
    use BaseCustomFieldHelperAwareTrait;
    use NormalizerAwareTrait;
    use ResultStatusCollectorAwareTrait;

    public const ERR_TYPE_INVALID = 1;
    public const ERR_NAME_REQUIRED = 2;
    public const ERR_ORDER_REQUIRED = 3;
    public const ERR_TYPE_REQUIRED = 4;
    public const ERR_PARAMETERS_INVALID = 5;
    public const ERR_NAME_EXIST = 6;
    public const ERR_ORDER_INVALID = 7;
    public const ERR_ORDER_EXIST = 8;
    public const ERR_NAME_INVALID = 9;
    public const ERR_BOOLEAN_INVALID = 10;
    public const ERR_PARAMETERS_FILE_INVALID = 11;
    public const ERR_PARAMETERS_FILE_REQUIRED = 12;
    public const ERR_PARAMETERS_INVALID_INTEGER = 13;
    public const ERR_PARAMETERS_SELECT_REQUIRED = 14;
    public const ERR_NAME_RESERVED_WORD = 15;

    protected const ERROR_MESSAGES = [
        self::ERR_NAME_EXIST => 'Already exist',
        self::ERR_NAME_INVALID => 'Invalid',
        self::ERR_NAME_REQUIRED => 'Required',
        self::ERR_NAME_RESERVED_WORD => 'Matches one of the reserved words',
        self::ERR_ORDER_EXIST => 'Order number already exist',
        self::ERR_ORDER_INVALID => 'Should be positive integer',
        self::ERR_ORDER_REQUIRED => 'Required',
        self::ERR_PARAMETERS_FILE_INVALID => 'Default value has wrong format',
        self::ERR_PARAMETERS_FILE_REQUIRED => 'Required',
        self::ERR_PARAMETERS_INVALID => '',
        self::ERR_PARAMETERS_INVALID_INTEGER => 'Value should be integer',
        self::ERR_PARAMETERS_SELECT_REQUIRED => 'Required',
        self::ERR_TYPE_INVALID => 'The value you selected is not a valid choice',
        self::ERR_TYPE_REQUIRED => 'Required',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param NormalizerInterface $normalizer
     * @return static
     */
    public function construct(NormalizerInterface $normalizer): static
    {
        $this->setNormalizer($normalizer);
        $this->getResultStatusCollector()->construct(static::ERROR_MESSAGES);
        return $this;
    }

    /**
     * @param AuctionCustomFieldEditFormDto $dto
     * @return bool
     */
    public function validate(AuctionCustomFieldEditFormDto $dto): bool
    {
        $this->getResultStatusCollector()->clear();
        $this->checkConstraints($dto);
        return !$this->hasErrors();
    }

    /**
     * @return ResultStatus[]
     */
    public function errorStatuses(): array
    {
        return $this->getResultStatusCollector()->getErrorStatuses();
    }

    /**
     * @return int[]
     * @internal
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    /**
     * @param AuctionCustomFieldEditFormDto $dto
     */
    private function checkConstraints(AuctionCustomFieldEditFormDto $dto): void
    {
        $isNew = !$dto->id;
        if ($isNew) {
            $this->checkRequired('name', self::ERR_NAME_REQUIRED, $dto);
        } else {
            $this->checkNotEmpty('name', self::ERR_NAME_REQUIRED, $dto);
        }
        $this->checkRequired('type', self::ERR_TYPE_REQUIRED, $dto);
        $this->checkNotEmpty('order', self::ERR_ORDER_REQUIRED, $dto);

        $this->checkType('name', Constants\Type::T_STRING, self::ERR_NAME_INVALID, $dto);
        $this->checkType('adminList', Constants\Type::T_BOOL, self::ERR_BOOLEAN_INVALID, $dto);
        $this->checkType('clone', Constants\Type::T_BOOL, self::ERR_BOOLEAN_INVALID, $dto);
        $this->checkType('publicList', Constants\Type::T_BOOL, self::ERR_BOOLEAN_INVALID, $dto);
        $this->checkType('required', Constants\Type::T_BOOL, self::ERR_BOOLEAN_INVALID, $dto);
        $this->checkFloatPositive('order', self::ERR_ORDER_INVALID, $dto);

        $this->checkChoice('type', self::ERR_TYPE_INVALID, $this->getAuctionCustomFieldEditFormDataProvider()->getAvailableTypes(), $dto);

        $this->checkNameUnique($dto);
        $this->checkNameAgainstReservedWords($dto);
        $this->checkOrderUnique($dto);

        switch ($dto->type) {
            case Constants\CustomField::TYPE_INTEGER:
            case Constants\CustomField::TYPE_DECIMAL:
                $this->checkType('parameters', Constants\Type::T_INTEGER, self::ERR_PARAMETERS_INVALID_INTEGER, $dto);
                break;
            case Constants\CustomField::TYPE_FILE:
                $this->checkNotEmpty('parameters', self::ERR_PARAMETERS_FILE_REQUIRED, $dto);
                $this->checkFileParameterFormat($dto);
                break;
            case Constants\CustomField::TYPE_SELECT:
                $this->checkRequired('parameters', self::ERR_PARAMETERS_SELECT_REQUIRED, $dto);
                break;
        }
    }

    /**
     * @param string $property
     * @param string $type
     * @param int $errorCode
     * @param $dto
     */
    private function checkType(string $property, string $type, int $errorCode, $dto): void
    {
        if (!isset($dto->{$property}) || $dto->{$property} === '') {
            return;
        }

        $value = $dto->{$property};
        $normalizer = $this->getNormalizer();
        $isValid = match ($type) {
            Constants\Type::T_FLOAT => $normalizer->isFloat($value),
            Constants\Type::T_INTEGER => $normalizer->isInt($value),
            Constants\Type::T_BOOL => $normalizer->isBoolable($value),
            Constants\Type::T_ARRAY => $normalizer->isList($value),
            Constants\Type::T_STRING => is_string($value),
            default => true
        };

        if (!$isValid) {
            $this->getResultStatusCollector()->addError($errorCode, null, ['property' => $property]);
        }
    }

    /**
     * @param string $property
     * @param int $errorCode
     * @param AuctionCustomFieldEditFormDto $dto
     */
    private function checkFloatPositive(string $property, int $errorCode, AuctionCustomFieldEditFormDto $dto): void
    {
        if (!isset($dto->{$property}) || $dto->{$property} === '') {
            return;
        }

        if (!$this->getNormalizer()->isFloatPositive($dto->{$property})) {
            $this->getResultStatusCollector()->addError($errorCode, null, ['property' => $property]);
        }
    }

    /**
     * @param AuctionCustomFieldEditFormDto $dto
     */
    private function checkNameUnique(AuctionCustomFieldEditFormDto $dto): void
    {
        if (!isset($dto->name) || $dto->name === '') {
            return;
        }

        if ($this->getAuctionCustomFieldEditFormDataProvider()->hasCustomFieldsWithName($dto->name, Cast::toInt($dto->id))) {
            $this->getResultStatusCollector()->addError(self::ERR_NAME_EXIST, null, ['property' => 'name']);
        }
    }

    private function checkNameAgainstReservedWords(AuctionCustomFieldEditFormDto $dto): void
    {
        if (!isset($dto->name) || $dto->name === '') {
            return;
        }
        $reservedWords = [];

        if (in_array($dto->name, $reservedWords, true)) { // @phpstan-ignore-line
            $this->getResultStatusCollector()->addError(self::ERR_NAME_RESERVED_WORD, null, ['property' => 'name']);
        }
    }

    /**
     * @param AuctionCustomFieldEditFormDto $dto
     */
    private function checkOrderUnique(AuctionCustomFieldEditFormDto $dto): void
    {
        if (
            !isset($dto->order)
            || $dto->order === ''
            || !NumberValidator::new()->isIntPositive($dto->order)
        ) {
            return;
        }

        if ($this->getAuctionCustomFieldEditFormDataProvider()->hasCustomFieldsWithOrderNumber((int)$dto->order, Cast::toInt($dto->id))) {
            $this->getResultStatusCollector()->addError(self::ERR_ORDER_EXIST, null, ['property' => 'order']);
        }
    }

    /**
     * @param string $property
     * @param int $errorCode
     * @param AuctionCustomFieldEditFormDto $dto
     */
    private function checkRequired(string $property, int $errorCode, AuctionCustomFieldEditFormDto $dto): void
    {
        if (!isset($dto->{$property}) || in_array($dto->{$property}, [null, ''], true)) {
            $this->getResultStatusCollector()->addError($errorCode, null, ['property' => $property]);
        }
    }

    /**
     * @param string $property
     * @param int $errorCode
     * @param AuctionCustomFieldEditFormDto $dto
     */
    private function checkNotEmpty(string $property, int $errorCode, AuctionCustomFieldEditFormDto $dto): void
    {
        if (!isset($dto->{$property})) {
            return;
        }

        if (in_array($dto->{$property}, [null, ''], true)) {
            $this->getResultStatusCollector()->addError($errorCode, null, ['property' => $property]);
        }
    }

    /**
     * @param string $property
     * @param int $errorCode
     * @param array $knownSet
     * @param AuctionCustomFieldEditFormDto $dto
     */
    private function checkChoice(string $property, int $errorCode, array $knownSet, AuctionCustomFieldEditFormDto $dto): void
    {
        if (!isset($dto->{$property}) || $dto->{$property} === '') {
            return;
        }
        if (!in_array($dto->{$property}, $knownSet, false)) {
            $this->getResultStatusCollector()->addError($errorCode, null, ['property' => $property]);
        }
    }

    /**
     * @param AuctionCustomFieldEditFormDto $dto
     */
    private function checkFileParameterFormat(AuctionCustomFieldEditFormDto $dto): void
    {
        if (!isset($dto->parameters) || $dto->parameters === '') {
            return;
        }

        if (!$this->getBaseCustomFieldHelper()->checkCustomFieldFileParameter($dto->parameters)) {
            $this->getResultStatusCollector()->addError(self::ERR_PARAMETERS_FILE_INVALID, null, ['property' => 'parameters']);
        }
    }
}
