<?php
/**
 * Normalizes the option value from the global configuration to the value that is presented
 * for this option in the web interface form.
 *
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           04-12, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Validate\Web\Filter;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Installation\Config\Edit\Meta\Descriptor\Descriptor;
use Sam\Installation\Config\Edit\Render\EditBlock\EditBlockRendererHelperCreateTrait;
use Sam\Installation\Config\Edit\Render\WebData\OptionInput\InputData\InputDataWeb;
use Sam\Installation\Config\Edit\Render\WebData\OptionInput\InputData\InputDataWebValueStructured;

/**
 * Class WebValueNormalizer
 * @package Sam\Installation\Config\Edit\Validate\Web\Filter
 */
class WebValueNormalizer extends CustomizableClass
{
    use EditBlockRendererHelperCreateTrait;

    /** @var array */
    protected const GENERAL_TYPES = [
        Constants\Type::T_BOOL,
        Constants\Type::T_STRING,
        Constants\Type::T_INTEGER,
        Constants\Type::T_FLOAT
    ];

    /** @var array */
    protected const ARRAY_TYPES = [
        Constants\Type::T_ARRAY,
        Constants\Installation::T_STRUCT_ARRAY
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
     * @param Descriptor $descriptor
     * @param bool $useLocalValue
     * @return mixed
     */
    public function normalize(Descriptor $descriptor, bool $useLocalValue = false): mixed
    {
        $type = $descriptor->getType();
        $workingValue = $useLocalValue
            ? $descriptor->getLocalValue()
            : $descriptor->getGlobalValue();

        if (in_array($type, self::GENERAL_TYPES, true)) {
            return $this->normalizeValueForGeneralTypes($workingValue, $type);
        }

        if (in_array($type, self::ARRAY_TYPES, true)) {
            if ($descriptor->getEditComponent() !== Constants\Installation::ECOM_MULTISELECT) {
                return $this->normalizeValueForArrayTypes($descriptor, $useLocalValue);
            }
            return $this->normalizeArrayForMultiselect($workingValue);
        }

        return $workingValue ?? '';
    }

    /**
     * @param mixed $value
     * @param string $type
     * @return string
     */
    protected function normalizeValueForGeneralTypes(mixed $value, string $type): string
    {
        $output = $type === Constants\Type::T_BOOL
            ? $this->normalizeBoolValue($value)
            : (string)$value;
        return $output;
    }

    /**
     * @param Descriptor $descriptor
     * @param bool $useLocalValue
     * @return string
     */
    protected function normalizeValueForArrayTypes(Descriptor $descriptor, bool $useLocalValue = false): string
    {
        $inputData = new InputDataWeb(
            null,
            $descriptor,
            $descriptor->getOptionKey()
        );
        $workingValue = $useLocalValue
            ? $descriptor->getLocalValue()
            : $descriptor->getGlobalValue();
        if ($descriptor->getType() === Constants\Installation::T_STRUCT_ARRAY) {
            $structuredValue = new InputDataWebValueStructured($workingValue);
            $inputData->setValueStructured($structuredValue);
        } else {
            $inputData->setValue($workingValue);
        }
        $output = $descriptor->getType() === Constants\Type::T_ARRAY
            ? $this->createEditBlockRendererHelper()->buildWebFormReadyInputValueFromArray($inputData)
            : $this->createEditBlockRendererHelper()->buildWebFormReadyInputValueFromStructuredArray($inputData);
        return $output;
    }

    /**
     * @param bool $value
     * @return string
     */
    protected function normalizeBoolValue(bool $value): string
    {
        return $value ? '1' : '0';
    }

    /**
     * @param array $valuesList
     * @return array
     */
    protected function normalizeArrayForMultiselect(array $valuesList): array
    {
        $output = [];
        foreach ($valuesList ?: [] as $value) {
            $output[] = (string)$value;
        }
        return $output;
    }

}
