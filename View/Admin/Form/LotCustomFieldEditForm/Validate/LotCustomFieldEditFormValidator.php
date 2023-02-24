<?php
/**
 * SAM-6308: Refactor custom field management to separate modules
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul. 21, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotCustomFieldEditForm\Validate;

use Sam\Core\Constants;
use Sam\Core\Data\Normalize\NormalizerAwareTrait;
use Sam\Core\Data\Normalize\NormalizerInterface;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\CustomField\Base\Help\BaseCustomFieldHelperAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\View\Admin\Form\LotCustomFieldEditForm\Dto\LotCustomFieldEditFormDto;
use Sam\View\Admin\Form\LotCustomFieldEditForm\Load\LotCustomFieldEditFormDataProviderAwareTrait;

/**
 * Class LotCustomFieldEditFormValidator
 * @package Sam\View\Admin\Form\LotCustomFieldEditForm\Validate
 */
class LotCustomFieldEditFormValidator extends CustomizableClass
{
    use BaseCustomFieldHelperAwareTrait;
    use ConfigRepositoryAwareTrait;
    use LotCustomFieldEditFormDataProviderAwareTrait;
    use NormalizerAwareTrait;
    use ResultStatusCollectorAwareTrait;

    public const ERR_ACCESS_INVALID = 1;
    public const ERR_ACCESS_REQUIRED = 11;
    public const ERR_BARCODE_TYPE_INVALID = 3;
    public const ERR_BOOLEAN_INVALID = 13;
    public const ERR_LOT_CATEGORIES_NOT_EXIST = 18;
    public const ERR_NAME_EXIST = 8;
    public const ERR_NAME_INVALID = 12;
    public const ERR_NAME_REQUIRED = 4;
    public const ERR_NAME_RESERVED_WORD = 19;
    public const ERR_ORDER_EXIST = 10;
    public const ERR_ORDER_INVALID = 9;
    public const ERR_ORDER_REQUIRED = 5;
    public const ERR_PARAMETERS_FILE_INVALID = 14;
    public const ERR_PARAMETERS_FILE_REQUIRED = 15;
    public const ERR_PARAMETERS_INVALID = 7;
    public const ERR_PARAMETERS_INVALID_INTEGER = 16;
    public const ERR_PARAMETERS_SELECT_REQUIRED = 17;
    public const ERR_TYPE_INVALID = 2;
    public const ERR_TYPE_REQUIRED = 6;

    protected const ERROR_MESSAGES = [
        self::ERR_ACCESS_INVALID => 'The value you selected is not a valid choice',
        self::ERR_ACCESS_REQUIRED => 'Required',
        self::ERR_BARCODE_TYPE_INVALID => 'The value you selected is not a valid choice',
        self::ERR_BOOLEAN_INVALID => 'Invalid boolean type',
        self::ERR_LOT_CATEGORIES_NOT_EXIST => 'Lot category does not exist',
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
     * Class instantiation method
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
     * @param LotCustomFieldEditFormDto $dto
     * @return bool
     */
    public function validate(LotCustomFieldEditFormDto $dto): bool
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
     * @param LotCustomFieldEditFormDto $dto
     */
    private function checkConstraints(LotCustomFieldEditFormDto $dto): void
    {
        $isNew = !$dto->id;
        if ($isNew) {
            $this->checkRequired('name', self::ERR_NAME_REQUIRED, $dto);
        } else {
            $this->checkNotEmpty('name', self::ERR_NAME_REQUIRED, $dto);
        }
        $this->checkRequired('type', self::ERR_TYPE_REQUIRED, $dto);
        $this->checkNotEmpty('order', self::ERR_ORDER_REQUIRED, $dto);
        $this->checkNotEmpty('access', self::ERR_ACCESS_REQUIRED, $dto);

        $this->checkType('name', Constants\Type::T_STRING, self::ERR_NAME_INVALID, $dto);
        $this->checkType('autoComplete', Constants\Type::T_BOOL, self::ERR_BOOLEAN_INVALID, $dto);
        $this->checkType('barcode', Constants\Type::T_BOOL, self::ERR_BOOLEAN_INVALID, $dto);
        $this->checkType('inAdminCatalog', Constants\Type::T_BOOL, self::ERR_BOOLEAN_INVALID, $dto);
        $this->checkType('inAdminSearch', Constants\Type::T_BOOL, self::ERR_BOOLEAN_INVALID, $dto);
        $this->checkType('inCatalog', Constants\Type::T_BOOL, self::ERR_BOOLEAN_INVALID, $dto);
        $this->checkType('inInvoices', Constants\Type::T_BOOL, self::ERR_BOOLEAN_INVALID, $dto);
        $this->checkType('inSettlements', Constants\Type::T_BOOL, self::ERR_BOOLEAN_INVALID, $dto);
        $this->checkType('lotNumAutoFill', Constants\Type::T_BOOL, self::ERR_BOOLEAN_INVALID, $dto);
        $this->checkType('searchField', Constants\Type::T_BOOL, self::ERR_BOOLEAN_INVALID, $dto);
        $this->checkType('searchIndex', Constants\Type::T_BOOL, self::ERR_BOOLEAN_INVALID, $dto);
        $this->checkType('unique', Constants\Type::T_BOOL, self::ERR_BOOLEAN_INVALID, $dto);
        $this->checkType('fckEditor', Constants\Type::T_BOOL, self::ERR_BOOLEAN_INVALID, $dto);
        $this->checkType('barcodeAutoPopulate', Constants\Type::T_BOOL, self::ERR_BOOLEAN_INVALID, $dto);
        $this->checkType('lotCategories', Constants\Type::T_ARRAY, self::ERR_BOOLEAN_INVALID, $dto);
        $this->checkIntPositive('order', self::ERR_ORDER_INVALID, $dto);

        $this->checkChoice('access', self::ERR_ACCESS_INVALID, Constants\Role::$roles, $dto);
        $this->checkChoice('type', self::ERR_TYPE_INVALID, $this->getLotCustomFieldEditFormDataProvider()->getAvailableTypes(), $dto);
        $this->checkChoice('barcodeType', self::ERR_BARCODE_TYPE_INVALID, array_keys(Constants\CustomField::$barcodeTypeNames), $dto);

        $this->checkNameUnique($dto);
        $this->checkNameAgainstReservedWords($dto);
        $this->checkOrderUnique($dto);
        $this->checkLotCategories($dto);

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
            default => true,
        };

        if (!$isValid) {
            $this->getResultStatusCollector()->addError($errorCode, null, ['property' => $property]);
        }
    }

    /**
     * @param string $property
     * @param int $errorCode
     * @param LotCustomFieldEditFormDto $dto
     */
    private function checkRequired(string $property, int $errorCode, LotCustomFieldEditFormDto $dto): void
    {
        if (!isset($dto->{$property}) || in_array($dto->{$property}, [null, ''], true)) {
            $this->getResultStatusCollector()->addError($errorCode, null, ['property' => $property]);
        }
    }

    /**
     * @param string $property
     * @param int $errorCode
     * @param LotCustomFieldEditFormDto $dto
     */
    private function checkNotEmpty(string $property, int $errorCode, LotCustomFieldEditFormDto $dto): void
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
     * @param LotCustomFieldEditFormDto $dto
     */
    private function checkChoice(string $property, int $errorCode, array $knownSet, LotCustomFieldEditFormDto $dto): void
    {
        if (!isset($dto->{$property}) || $dto->{$property} === '') {
            return;
        }
        if (!in_array($dto->{$property}, $knownSet, false)) {
            $this->getResultStatusCollector()->addError($errorCode, null, ['property' => $property]);
        }
    }

    /**
     * @param string $property
     * @param int $errorCode
     * @param LotCustomFieldEditFormDto $dto
     */
    private function checkIntPositive(string $property, int $errorCode, LotCustomFieldEditFormDto $dto): void
    {
        if (!isset($dto->{$property}) || $dto->{$property} === '') {
            return;
        }

        if (!$this->getNormalizer()->isIntPositive($dto->{$property})) {
            $this->getResultStatusCollector()->addError($errorCode, null, ['property' => $property]);
        }
    }

    /**
     * @param LotCustomFieldEditFormDto $dto
     */
    private function checkNameUnique(LotCustomFieldEditFormDto $dto): void
    {
        if (!isset($dto->name) || $dto->name === '') {
            return;
        }

        if ($this->getLotCustomFieldEditFormDataProvider()->hasCustomFieldsWithName($dto->name, Cast::toInt($dto->id))) {
            $this->getResultStatusCollector()->addError(self::ERR_NAME_EXIST, null, ['property' => 'name']);
        }
    }

    private function checkNameAgainstReservedWords(LotCustomFieldEditFormDto $dto): void
    {
        if (!isset($dto->name) || $dto->name === '') {
            return;
        }
        $reservedWords = array_merge(
            array_values($this->cfg()->get('csv->admin->live')->toArray()),
            array_values($this->cfg()->get('csv->admin->timed')->toArray()),
            array_values($this->cfg()->get('csv->admin->inventory')->toArray()),
        );

        if (in_array($dto->name, $reservedWords, true)) {
            $this->getResultStatusCollector()->addError(self::ERR_NAME_RESERVED_WORD, null, ['property' => 'name']);
        }
    }

    /**
     * @param LotCustomFieldEditFormDto $dto
     */
    private function checkOrderUnique(LotCustomFieldEditFormDto $dto): void
    {
        if (
            !isset($dto->order)
            || $dto->order === ''
            || !NumberValidator::new()->isIntPositive($dto->order)
        ) {
            return;
        }

        if ($this->getLotCustomFieldEditFormDataProvider()->hasCustomFieldsWithOrderNumber((int)$dto->order, Cast::toInt($dto->id))) {
            $this->getResultStatusCollector()->addError(self::ERR_ORDER_EXIST, null, ['property' => 'order']);
        }
    }

    /**
     * @param LotCustomFieldEditFormDto $dto
     */
    private function checkFileParameterFormat(LotCustomFieldEditFormDto $dto): void
    {
        if (!isset($dto->parameters) || $dto->parameters === '') {
            return;
        }

        if (!$this->getBaseCustomFieldHelper()->checkCustomFieldFileParameter($dto->parameters)) {
            $this->getResultStatusCollector()->addError(self::ERR_PARAMETERS_FILE_INVALID, null, ['property' => 'parameters']);
        }
    }

    /**
     * @param LotCustomFieldEditFormDto $dto
     */
    private function checkLotCategories(LotCustomFieldEditFormDto $dto): void
    {
        if (!isset($dto->lotCategories) || !$this->getNormalizer()->toList($dto->lotCategories)) {
            return;
        }

        $lotCategories = $this->getNormalizer()->toList($dto->lotCategories);
        foreach ($lotCategories as $lotCategoryId) {
            if (!$this->getLotCustomFieldEditFormDataProvider()->isExistLotCategory((int)$lotCategoryId)) {
                $this->getResultStatusCollector()->addError(self::ERR_LOT_CATEGORIES_NOT_EXIST, null, ['property' => 'lotCategories']);
                break;
            }
        }
    }
}
