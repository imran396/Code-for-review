<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           09-15, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Meta\Descriptor;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Installation\Config\Edit\Meta\Constraint\DescriptorAdditionalConstraintsBuilder;
use Sam\Installation\Config\Edit\Meta\Constraint\DescriptorConstraintsBuilderAwareTrait;
use Sam\Installation\Config\Edit\Meta\Constraint\DescriptorConstraintsBuilderHelper;
use Sam\Installation\Config\Edit\Meta\OptionHelperAwareTrait;

/**
 * Class DescriptorBuilder
 * @package Sam\Installation\Config
 */
class DescriptorBuilder extends CustomizableClass
{
    use DescriptorConstraintsBuilderAwareTrait;
    use OptionHelperAwareTrait;

    /**
     * Global config options with _general_ delimiter flat option keys.
     * @var array
     */
    protected array $globalOptions = [];

    /**
     * Local config options with flat keys.
     * @var array
     */
    protected array $localOptions = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Build meta one dimension array with meta descriptors. Each key of returned array
     * is an instance of Sam\Installation\Config\Edit\Meta\Descriptor class with valid Descriptor class
     * properties values for current key of returned array.
     * @param array $metaOptions
     * @return Descriptor
     */
    public function buildFromMetaArray(array $metaOptions): Descriptor
    {
        $descriptor = Descriptor::new()
            ->enableVisible(true)
            ->enableEditable(true)
            ->enableDeletable(true);
        $descriptorConstraintsBuilderHelper = DescriptorConstraintsBuilderHelper::new();

        foreach ($metaOptions as $metaOptionKey => $metaAttributes) {
            $optionKey = $this->getOptionHelper()->replaceMetaToGeneralDelimiter($metaOptionKey);
            $descriptor->setOptionKey($optionKey);
            foreach ($metaAttributes ?: [] as $attribute => $value) {
                if (!in_array($attribute, Constants\Installation::AVAILABLE_META_ATTRIBUTES, true)) {
                    log_error('Incorrect option attribute' . composeSuffix(['passed' => $attribute]));
                    continue;
                }
                if ($attribute === Constants\Installation::META_ATTR_EDIT_COMPONENT) {
                    $descriptor->setEditComponent($value);
                } elseif ($attribute === Constants\Installation::META_ATTR_DESCRIPTION) {
                    $descriptor->setDescription($value);
                } elseif ($attribute === Constants\Installation::META_ATTR_EDITABLE) {
                    $descriptor->enableEditable((bool)$value);
                } elseif ($attribute === Constants\Installation::META_ATTR_DELETABLE) {
                    $descriptor->enableDeletable((bool)$value);
                } elseif ($attribute === Constants\Installation::META_ATTR_KNOWN_SET) {
                    $descriptor->setKnownSet($value);
                } elseif ($attribute === Constants\Installation::META_ATTR_KNOWN_SET_NAMES) {
                    $descriptor->setKnownSetNames($value);
                } elseif ($attribute === Constants\Installation::META_ATTR_TYPE) {
                    $descriptor->setType($value);
                    $descriptor = $descriptorConstraintsBuilderHelper->addConstraintsForDataType($descriptor);
                } elseif ($attribute === Constants\Installation::META_ATTR_VISIBLE) {
                    $descriptor->enableVisible((bool)$value);
                } elseif ($attribute === Constants\Installation::META_ATTR_CONSTRAINTS) {
                    $constraints = $descriptorConstraintsBuilderHelper->mergeConstraints(
                        $descriptor->getConstraints(),
                        $value
                    );
                    $descriptor->setConstraints($constraints);
                }
            }
            $descriptor = $this->normalizeEditComponent($descriptor);
        }
        $descriptor = DescriptorAdditionalConstraintsBuilder::new()->addAdditionalConstraints($descriptor);

        return $descriptor;
    }

    /**
     * Produce flat option key array with meta descriptors
     * @param array $metaPathKeyMetaOptions - meta options array with flat keys (meta option delimiter)
     * @return array
     */
    public function buildFormMultipleMetaArray(array $metaPathKeyMetaOptions): array
    {
        $descriptors = [];
        $optionHelper = $this->getOptionHelper();
        foreach ($metaPathKeyMetaOptions ?: [] as $metaOptionKey => $metaAttributes) {
            $descriptor = $this->buildFromMetaArray([$metaOptionKey => $metaAttributes]);
            $optionKey = $optionHelper->replaceMetaToGeneralDelimiter($metaOptionKey);
            $descriptors[$optionKey] = $descriptor;
        }
        return $descriptors;
    }

    /**
     * @param Descriptor $descriptor
     * @return Descriptor
     */
    public function addOptionValues(Descriptor $descriptor): Descriptor
    {
        $optionKey = $descriptor->getOptionKey();
        $descriptor->setGlobalValue($this->findGlobalValueByKey($optionKey));
        if (array_key_exists($optionKey, $this->getLocalOptions())) {
            $descriptor->setLocalValue($this->findLocalValueByKey($optionKey));
        }
        return $descriptor;
    }

    /**
     * @param string $optionKey
     * @param mixed $value
     * @return MissingDescriptor
     */
    public function buildForMissedOption(string $optionKey, mixed $value): MissingDescriptor
    {
        $descriptor = MissingDescriptor::new()
            ->setOptionKey($optionKey)
            ->setLocalValue($value);
        $descriptor = $this->setTypeForDescriptorFromConfigValue($descriptor, $value);
        $descriptor = $this->normalizeEditComponent($descriptor);
        return $descriptor;
    }

    /**
     * Set type attribute for meta Descriptor instance.
     * @template TDescriptor of Descriptor
     * @param TDescriptor $descriptor
     * @param mixed $value
     * @return TDescriptor
     */
    public function setTypeForDescriptorFromConfigValue(Descriptor $descriptor, mixed $value): Descriptor
    {
        $type = Cast::toString(gettype($value), $descriptor::AVAILABLE_DATA_TYPES);
        $type = $type ?? Constants\Installation::T_DEFAULT;
        $descriptor->setType($type);
        return $descriptor;
    }

    /**
     * @return array
     */
    public function getGlobalOptions(): array
    {
        return $this->globalOptions;
    }

    /**
     * @param array $globalOptions
     * @return $this
     */
    public function setGlobalOptions(array $globalOptions): static
    {
        $this->globalOptions = $globalOptions;
        return $this;
    }

    /**
     * @return array
     */
    public function getLocalOptions(): array
    {
        return $this->localOptions;
    }

    /**
     * @param array $localOptions
     * @return $this
     */
    public function setLocalOptions(array $localOptions): static
    {
        $this->localOptions = $localOptions;
        return $this;
    }

    /**
     * @param string $optionKey
     * @return mixed
     */
    private function findGlobalValueByKey(string $optionKey): mixed
    {
        $globalOptions = $this->getGlobalOptions();
        $output = $globalOptions[$optionKey];
        return $output;
    }

    /**
     * @param string $optionKey
     * @return mixed
     */
    private function findLocalValueByKey(string $optionKey): mixed
    {
        $localOptions = $this->getLocalOptions();
        $output = $localOptions[$optionKey];
        return $output;
    }

    /**
     * Assign edit component default type, when it is absent.
     *
     * @template TDescriptor of Descriptor
     * @param TDescriptor $descriptor
     * @return TDescriptor
     */
    private function normalizeEditComponent(Descriptor $descriptor): Descriptor
    {
        if (!$descriptor->isEditComponentAbsent()) {
            return $descriptor;
        }

        if (in_array($descriptor->getType(), [Constants\Type::T_ARRAY, Constants\Installation::T_STRUCT_ARRAY], true)) {
            $descriptor->setEditComponent(Constants\Installation::ECOM_DEFAULT_FOR_ARRAYS);
            return $descriptor;
        }

        $descriptor->setEditComponent(Constants\Installation::ECOM_DEFAULT);
        return $descriptor;
    }
}
