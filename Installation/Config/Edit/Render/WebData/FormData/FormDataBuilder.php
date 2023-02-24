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

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Edit\Meta\Descriptor\MissingDescriptor;
use Sam\Installation\Config\Edit\Render\WebData\Option\OptionDefaultValueRenderer;
use Sam\Installation\Config\Edit\Render\WebData\OptionInput\InputData\InputDataWeb;
use Sam\Installation\Config\Edit\Render\WebData\OptionInput\Validation\OptionInputValidationWebData;
use Sam\Installation\Config\Edit\Validate\Web\ValidatedData;

/**
 * Class FormDataBuilder
 * @package Sam\Installation\Config
 */
class FormDataBuilder extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param ValidatedData[] $validatedData simple one dimension value array with flat option keys.
     * @param array $formData
     * @return array
     */
    public function build(array $validatedData, array $formData): array
    {
        $output = [];
        foreach ($formData as $configArea => $configAreaData) {
            /** @var InputDataWeb $inputData */
            foreach ($configAreaData as $optionKey => $inputData) {
                $descriptor = $inputData->getMetaDescriptor();
                $validation = $this->buildOptionInputValidationWebData($optionKey, $validatedData);
                $inputData->setValidation($validation);
                if ($descriptor->hasLocalValue()) {
                    // setting up default value for input
                    $defaultValue = $descriptor instanceof MissingDescriptor
                        ? 'Warning: No default value.'
                        : OptionDefaultValueRenderer::new()->render($descriptor->getGlobalValue(), $descriptor->getType());
                    $inputData->setValueDefault($defaultValue);
                }
                $output[$configArea][$optionKey] = $inputData;
            }
        }

        return $output;
    }

    /**
     * @param string $optionKey
     * @param ValidatedData[] $validatedData
     * @return OptionInputValidationWebData
     */
    protected function buildOptionInputValidationWebData(string $optionKey, array $validatedData): OptionInputValidationWebData
    {
        $postValue = null;
        $validationStatus = Constants\Installation::V_STATUS_DEFAULT;
        $validationErrorMessages = [];
        foreach ($validatedData as $oKey => $validatedDatum) {
            if ($optionKey === $oKey) {
                $validationStatus = empty($validatedDatum->getValidationResults())
                    ? Constants\Installation::V_STATUS_SUCCESS
                    : Constants\Installation::V_STATUS_FAIL;
                $postValue = $validatedDatum->getValue();
                $validationErrorMessages = $validatedDatum->getValidationResults();
                break;
            }
        }
        $output = new OptionInputValidationWebData(
            $validationStatus,
            $postValue,
            $validationErrorMessages
        );

        return $output;
    }
}
