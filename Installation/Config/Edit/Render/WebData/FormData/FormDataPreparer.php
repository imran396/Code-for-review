<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           09-13, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Render\WebData\FormData;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Installation\Config\Edit\Meta\Collection\DescriptorCollection;
use Sam\Installation\Config\Edit\Meta\Descriptor\Descriptor;
use Sam\Installation\Config\Edit\Meta\Descriptor\MissingDescriptor;
use Sam\Installation\Config\Edit\Meta\OptionHelperAwareTrait;
use Sam\Installation\Config\Edit\Render\WebData\Area\Statistic\AreaStatisticItemWebData;
use Sam\Installation\Config\Edit\Render\WebData\FormData\Dto\PreparedFormDataDto;
use Sam\Installation\Config\Edit\Render\WebData\OptionInput\InputData\InputDataWeb;
use Sam\Installation\Config\Edit\Render\WebData\OptionInput\InputData\InputDataWebValueStructured;

/**
 * Class FormDataPreparer
 * @package Sam\Installation\Config
 */
class FormDataPreparer extends CustomizableClass
{
    use OptionHelperAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Build web-ready final data array for rendering web-interface form.
     * @param DescriptorCollection $descriptorCollection
     * @return PreparedFormDataDto
     */
    public function prepareFormData(DescriptorCollection $descriptorCollection): PreparedFormDataDto
    {
        $optionHelper = $this->getOptionHelper();
        $actualOptions = $optionHelper->toActualOptions($descriptorCollection);
        $multiDimActualOptions = $optionHelper->transformFlatKeyToMultiDimKeyOptions($actualOptions);
        $multiDimMetaDescriptors = $optionHelper->transformFlatKeyToMultiDimKeyOptions($descriptorCollection->toArray());
        $configName = $descriptorCollection->getConfigName();

        $statistics = $formData = [];
        foreach ($multiDimActualOptions as $mainBranch => $cValue) {
            $visibleStat = new AreaStatisticItemWebData('Visible', 'col-sm-2 col-md-2', 0, 0);
            $editableStat = new AreaStatisticItemWebData('Editable', 'col-sm-4 col-md-4', 0, 0);

            $multiDimBranchMetaDescriptors = $multiDimMetaDescriptors[$mainBranch] ?? [];
            $configAreaPrefix = $mainBranch . Constants\Installation::DELIMITER_GENERAL_OPTION_KEY;
            $prepareGroupInputs = $this->prepareInputData($cValue, $multiDimBranchMetaDescriptors, [], $configAreaPrefix, $configName);
            /** @var InputDataWeb $inputData */
            foreach ($prepareGroupInputs as $propName => $inputData) {
                $descriptor = $inputData->getMetaDescriptor();

                /** Building statistics for visible config options */
                if ($descriptor->isVisible()) {
                    $formData[$mainBranch][$propName] = $inputData;
                    $visibleStat = $visibleStat->incrementPresence();
                } else {
                    $visibleStat = $visibleStat->incrementAbsence();
                }

                if (!$descriptor instanceof MissingDescriptor) {
                    /** Building statistics for editable config options */
                    if ($descriptor->isEditable()) {
                        $editableStat = $editableStat->incrementPresence();
                    } else {
                        $editableStat = $editableStat->incrementAbsence();
                    }
                }
            }

            $statistics[$mainBranch] = [$visibleStat, $editableStat];
        }

        return new PreparedFormDataDto($formData, $statistics);
    }

    /**
     * @param mixed $in
     * @param array|Descriptor $meta
     * @param array $out
     * @param string $prefix
     * @param string $configName
     * @return array
     */
    protected function prepareInputData(
        mixed $in,
        array|Descriptor $meta = [],
        array $out = [],
        string $prefix = '',
        string $configName = ''
    ): array {
        $delimiter = Constants\Installation::DELIMITER_GENERAL_OPTION_KEY;
        if ($meta instanceof Descriptor) {
            $descriptor = $meta;
            $metaType = $descriptor->getType();
            $prefix = substr($prefix, 0, strlen($prefix) - strlen($delimiter));
            $normalizedValue = $metaType === Constants\Installation::T_STRUCT_ARRAY ? null : $in;

            $inputDataWeb = new InputDataWeb($normalizedValue, $descriptor, $prefix);
            if ($metaType === Constants\Installation::T_STRUCT_ARRAY) {
                $structuredValue = new InputDataWebValueStructured($in);
                $inputDataWeb->setValueStructured($structuredValue);
            }
            $out[$prefix] = $inputDataWeb;
        } elseif (is_array($meta)) {
            foreach ($in as $key => $value) {
                $descriptor = $meta[$key] ?? [];
                $metaType = $descriptor instanceof Descriptor ? $descriptor->getType() : null;
                $normalizedDescriptor = $descriptor instanceof Descriptor ? $descriptor : null;
                $label = "{$configName}{$delimiter}{$prefix}{$key}";

                if (is_array($value)) {
                    if (in_array($metaType, [Constants\Type::T_ARRAY, Constants\Installation::T_STRUCT_ARRAY], true)) {
                        $normalizedValue = $metaType === Constants\Installation::T_STRUCT_ARRAY ? null : $value;

                        $inputDataWeb = new InputDataWeb($normalizedValue, $normalizedDescriptor, $label);
                        if ($metaType === Constants\Installation::T_STRUCT_ARRAY) {
                            $structuredValue = new InputDataWebValueStructured($value);
                            $inputDataWeb->setValueStructured($structuredValue);
                        }
                        $out["{$prefix}{$key}"] = $inputDataWeb;
                    } else {
                        $writePrefix = $prefix . $key . $delimiter;
                        $out = array_merge($out, $this->prepareInputData($value, $descriptor, $out, $writePrefix, $configName));
                    }
                } else {
                    $out["{$prefix}{$key}"] = new InputDataWeb($value, $normalizedDescriptor, $label);
                }
            }
        }
        return $out;
    }
}
