<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           02/06/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Meta\Descriptor;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;

/**
 * Class Descriptor
 * Available meta attributes:
 * ['description', 'knownSet', 'knownSetNames', 'editComponent', 'editable', 'type', 'constraints', 'visible', 'deletable']
 *
 * More about meta attribute 'constraints':
 * this meta attribute will be used for input data validation.
 *
 * Describes meta config structure.
 * @package Sam\Installation\Config
 * @author: Yura Vakulenko
 */
class Descriptor extends CustomizableClass
{
    /**
     * Available meta types
     * @var string[]
     */
    public const AVAILABLE_DATA_TYPES = [
        Constants\Installation::T_STRUCT_ARRAY,
        Constants\Type::T_ARRAY,
        Constants\Type::T_BOOL,
        Constants\Type::T_FLOAT,
        Constants\Type::T_INTEGER,
        Constants\Type::T_NULL,
        Constants\Type::T_STRING,
    ];

    /**
     * Option value from global config.
     * @var mixed
     */
    protected mixed $globalValue = null;

    /**
     * Option value from local config.
     * @var mixed
     */
    protected mixed $localValue = null;

    /**
     * Known set (bundle) of available values for config option from Constants.
     * use following format: ['some value', 1, 1.1]
     *
     * @var array
     */
    protected array $knownSet = [];

    /**
     * Known set names. (bundle) of available values for config option from Constants.
     * use following format:
     * ['value1' =>'bundle item1 title(+short descr)', 'value2' =>'bundle item2 title(+short descr.)', ... infinity]
     * where 'value1', 'value2' - bundle item values for POST requests; 'bundle item1 title(+short descr.)'- bundle item
     * title + short description (optional) for web-interface form.
     *
     * @var array
     */
    protected array $knownSetNames = [];

    /**
     * @var string|null
     */
    protected ?string $editComponent = null;

    /**
     * Meta option. Meta index description for web-interface.
     * @var string
     */
    protected string $description = '';

    /**
     * Meta option. Is meta index editable value for web-interface.
     * @var bool
     */
    protected bool $isEditable = false;

    /**
     * Meta option. Is meta index visible value for web-interface.
     * @var bool
     */
    protected bool $isVisible = false;

    /**
     * Meta option. Whether it is allowed to delete the configuration parameter value is allowed using the web interface.
     * @var bool
     */
    protected bool $isDeletable = false;

    /**
     * Config option flat key with _general_ path delimiter
     * @var string|null
     */
    protected ?string $optionKey = null;

    /**
     * Meta option. Data type for meta index key.
     * @var string|null
     */
    protected ?string $type = null;

    /**
     * Meta option. Validation constraints array of validation rules.
     * @var array
     */
    protected array $constraints = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Get bundle
     * @return array
     */
    public function getKnownSet(): array
    {
        return $this->knownSet;
    }

    /**
     * Setup bundle of known sets from Constants.
     * @param array $knownSet
     * @return static
     */
    public function setKnownSet(array $knownSet): static
    {
        $this->knownSet = $knownSet;
        return $this;
    }

    /**
     * Get bundle
     * @return array
     */
    public function getKnownSetNames(): array
    {
        return $this->knownSetNames;
    }

    /**
     * Setup bundle of known sets from Constants.
     * @param array $knownSetNames
     * @return static
     */
    public function setKnownSetNames(array $knownSetNames): static
    {
        $this->knownSetNames = $knownSetNames;
        return $this;
    }

    /**
     * @return string
     */
    public function getEditComponent(): string
    {
        return $this->editComponent;
    }

    /**
     * @param string $value
     * @return static
     */
    public function setEditComponent(string $value): static
    {
        $this->editComponent = Cast::toString($value, Constants\Installation::AVAILABLE_EDIT_COMPONENTS);
        return $this;
    }

    public function isEditComponentAbsent(): bool
    {
        return $this->editComponent === null;
    }

    /**
     * @return bool
     */
    public function hasDescription(): bool
    {
        return $this->description !== '';
    }

    /**
     * Get meta attribute 'description'
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set meta attribute 'description'
     * @param string $value
     * @return static
     */
    public function setDescription(string $value): static
    {
        $this->description = trim($value);
        return $this;
    }

    /**
     * Get meta attribute 'editable'
     * @return bool
     */
    public function isEditable(): bool
    {
        return $this->isEditable;
    }

    /**
     * Set meta attribute 'editable'
     * @param bool $enable
     * @return static
     */
    public function enableEditable(bool $enable): static
    {
        $this->isEditable = $enable;
        return $this;
    }

    /**
     * @param string $optionKey
     * @return static
     */
    public function setOptionKey(string $optionKey): static
    {
        $this->optionKey = trim($optionKey);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOptionKey(): ?string
    {
        return $this->optionKey;
    }

    /**
     * Get meta attribute 'type'
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Set meta attribute 'type'
     * @param string $type
     * @return static
     */
    public function setType(string $type): static
    {
        $this->type = Cast::toString($type, self::AVAILABLE_DATA_TYPES);
        return $this;
    }

    /**
     * Get meta attribute 'constraints'
     * @return array
     */
    public function getConstraints(): array
    {
        return $this->constraints;
    }

    /**
     * Set meta attribute 'constraints'
     * @param array $constraints
     * @return static
     */
    public function setConstraints(array $constraints): static
    {
        $this->constraints = $constraints;
        return $this;
    }

    /**
     * Get meta attribute 'visible'
     * @return bool
     */
    public function isVisible(): bool
    {
        return $this->isVisible;
    }

    /**
     * Set meta attribute 'visible'
     * @param bool $enable
     * @return static
     */
    public function enableVisible(bool $enable): static
    {
        $this->isVisible = $enable;
        return $this;
    }

    /**
     * Current running value
     * @return mixed
     */
    public function getActualValue(): mixed
    {
        return $this->getLocalValue() ?? $this->getGlobalValue();
    }

    /**
     * @return mixed
     */
    public function getGlobalValue(): mixed
    {
        return $this->globalValue;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setGlobalValue(mixed $value): static
    {
        $this->globalValue = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasLocalValue(): bool
    {
        return $this->localValue !== null;
    }

    /**
     * @return mixed
     */
    public function getLocalValue(): mixed
    {
        return $this->localValue;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setLocalValue(mixed $value): static
    {
        $this->localValue = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDeletable(): bool
    {
        return $this->isDeletable;
    }

    /**
     * @param bool $enable
     * @return $this
     */
    public function enableDeletable(bool $enable): static
    {
        $this->isDeletable = $enable;
        return $this;
    }
}
