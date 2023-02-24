<?php
/**
 * Abstract Helper for DTO
 *
 * SAM-3874 Refactor SOAP service and apply unit tests
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 3, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Base\Dto;

use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use LotCategory;
use Sam\EntityMaker\AuctionLot\Dto\AuctionLotMakerInputDto;
use Sam\EntityMaker\Base\Common\Mode;
use Sam\EntityMaker\LotItem\Dto\LotItemMakerInputDto;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;

/**
 * Class DtoHelper
 * @package Sam\EntityMaker\Base
 */
abstract class DtoHelper extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use EntityFactoryCreateTrait;
    use LotCategoryLoaderAwareTrait;

    public const EMPTY_STRING_CLEAR = 'clear';
    public const EMPTY_STRING_DEFAULT = 'default';
    public const EMPTY_STRING_NO_ACTION = '';

    /**
     * @var LotCategory|null
     */
    private ?LotCategory $firstCategory = null;
    /**
     * @var Mode
     */
    protected Mode $mode;

    /**
     * @var string[]
     */
    public array $clearSigns;

    /**
     * @var string[]
     */
    public array $defaultSigns;

    /**
     * @var string[] (clear|default|'')
     */
    public array $controlCodes;

    public function construct(Mode $mode): static
    {
        $this->mode = $mode;
        $this->controlCodes = [
            self::EMPTY_STRING_CLEAR => $this->cfg()->get('core->soap->clearValue'),
            self::EMPTY_STRING_DEFAULT => $this->cfg()->get('core->soap->defaultValue'),
            self::EMPTY_STRING_NO_ACTION => '',
        ];
        $this->clearSigns = [
            Mode::CSV->name => $this->cfg()->get('core->csv->clearValue'),
            Mode::SOAP->name => $this->cfg()->get('core->soap->clearValue'),
            Mode::WEB_ADMIN->name => '',
            Mode::WEB_RESPONSIVE->name => '',
        ];
        $this->defaultSigns = [
            Mode::CSV->name => $this->cfg()->get('core->csv->defaultValue'),
            Mode::SOAP->name => $this->cfg()->get('core->soap->defaultValue'),
            Mode::WEB_ADMIN->name => '',
            Mode::WEB_RESPONSIVE->name => '',
        ];
        return $this;
    }

    /**
     * Prepare values
     * @template T of InputDto
     * @param T $inputDto
     * @param ConfigDto $configDto
     * @return T
     */
    public function prepareValues($inputDto, $configDto): InputDto
    {
        if ($configDto->isInputDtoReady) {
            return $inputDto;
        }

        $inputDto = $this->trimValues($inputDto);

        if ($this->mode->isSoap()) {
            $this->determineEmptyStringBehavior($inputDto);
        }

        if (
            $this->mode->isCsv()
            || $this->mode->isSoap()
        ) {
            /**
             * 'clearValues' is defined in Lot and User CSV imports by checkbox option:
             * On true - empty cell will clear or reset the field to its default value;
             * On false - empty cell will be ignored.
             */
            $isClearEmptyFields = $configDto->clearValues;
            if (!$isClearEmptyFields) {
                $this->ignoreEmptyProperties($inputDto);
                $this->replaceClearValueSignByEmptyString($inputDto);
            } else {
                $this->ignoreEmptyPropertiesNotPresentedInCsvFile($inputDto, $configDto);
            }
            $this->replaceDefaultValueSignByFieldDefaultValue($inputDto, $configDto);
        }

        $this->setDefaultValues($inputDto, $configDto);
        $configDto->isInputDtoReady = true;
        return $inputDto;
    }

    /**
     * @return string
     */
    protected function getClearSign(): string
    {
        return $this->clearSigns[$this->mode->name];
    }

    /**
     * @return string
     */
    protected function getDefaultSign(): string
    {
        return $this->defaultSigns[$this->mode->name];
    }

    /**
     * Get first incoming category
     * @param AuctionLotMakerInputDto|LotItemMakerInputDto $inputDto
     * @return LotCategory
     */
    protected function getFirstCategory(AuctionLotMakerInputDto|LotItemMakerInputDto $inputDto): LotCategory
    {
        if ($this->firstCategory === null) {
            $categoriesNames = $inputDto->categoriesNames;

            $category = null;
            if ($categoriesNames) {
                $category = $this->getLotCategoryLoader()->loadByName(trim($categoriesNames[0]));
            }
            $this->firstCategory = $category ?: $this->createEntityFactory()->lotCategory();
        }
        return $this->firstCategory;
    }

    protected function isCustomField(string $fieldName): bool
    {
        return str_starts_with($fieldName, 'customField');
    }

    /**
     * @param InputDto $inputDto
     * @return InputDto
     */
    protected function trimValues(InputDto $inputDto): InputDto
    {
        foreach ($inputDto->toArray() as $property => $value) {
            $inputDto->$property = (is_scalar($value) && !is_bool($value)) ? trim($value) : $value;
        }
        return $inputDto;
    }

    /**
     * Determine empty string behavior
     * @param InputDto $inputDto
     */
    protected function determineEmptyStringBehavior(InputDto $inputDto): void
    {
        foreach ($inputDto->toArray() as $key => $value) {
            if ($value === null || $value === '') {
                $action = $this->controlCodes[$this->cfg()->get('core->soap->emptyStringBehavior')];
                if ($action) {
                    $inputDto->$key = $action;
                } else {
                    unset($inputDto->$key);
                }
            }
        }
    }

    /**
     * Ignore empty properties null, '', []
     * [] is used to clear complex fields in SOAP
     * @param InputDto $inputDto
     */
    protected function ignoreEmptyProperties(InputDto $inputDto): void
    {
        $emptyProperties = $this->mode->isCsv()
            ? [null, '', []]
            : [null, ''];

        foreach ($inputDto->toArray() as $key => $value) {
            if (in_array($value, $emptyProperties, true)) {
                unset($inputDto->$key);
            }
        }
    }

    /**
     * Ignore empty properties not presented in csv file null, '', []
     * @param InputDto $inputDto
     * @param ConfigDto $configDto
     */
    protected function ignoreEmptyPropertiesNotPresentedInCsvFile(InputDto $inputDto, ConfigDto $configDto): void
    {
        $presentedCsvColumns = $configDto->presentedCsvColumns;
        foreach ($inputDto->toArray() as $key => $value) {
            if (
                in_array($value, [null, '', []], true)
                && !in_array($key, $presentedCsvColumns, true)
            ) {
                unset($inputDto->$key);
            }
        }
    }

    /**
     * Replace cfg()->core->csv/soap->clearValue sign by null
     * @param InputDto $inputDto
     */
    protected function replaceClearValueSignByEmptyString(InputDto $inputDto): void
    {
        foreach ($inputDto->toArray() as $key => $value) {
            if ($value === $this->getClearSign()) {
                $inputDto->$key = '';
            }
            if ($value === [$this->getClearSign()]) {
                $inputDto->$key = [];
            }
        }
    }

    /**
     * Replace cfg()->core->csv/soap->defaultValue sign by field's default value if exist
     * @param InputDto $inputDto
     * @param ConfigDto $configDto
     */
    protected function replaceDefaultValueSignByFieldDefaultValue(InputDto $inputDto, ConfigDto $configDto): void
    {
        foreach ($inputDto->toArray() as $key => $value) {
            if (in_array($value, [$this->getDefaultSign(), [$this->getDefaultSign()]], true)) {
                if (!$this->populateWithDefault($key, $inputDto, $configDto)) {
                    // If a user wants to set default value and field does not have it - set it empty string
                    $inputDto->$key = '';
                }
            }
        }
    }

    /**
     * Set default values
     * @param InputDto $inputDto
     * @param ConfigDto $configDto
     */
    protected function setDefaultValues(InputDto $inputDto, ConfigDto $configDto): void
    {
        if (!$inputDto->id) {
            foreach ($inputDto->knownKeys() as $key) {
                if (!isset($inputDto->$key)) {
                    $this->populateWithDefault($key, $inputDto, $configDto);
                }
            }
        }
    }

    /**
     * Set field default value. Can be overridden in child classes
     * @param string $field
     * @param InputDto $inputDto
     * @param ConfigDto $configDto
     * @return bool @c true if default value was set, @c false otherwise
     * @noinspection PhpUnusedParameterInspection
     */
    protected function populateWithDefault(string $field, InputDto $inputDto, ConfigDto $configDto): bool
    {
        return false;
    }
}
