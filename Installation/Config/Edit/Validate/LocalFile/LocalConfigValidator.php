<?php
/**
 * SAM-5306: Local installation correctness check
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 24, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Validate\LocalFile;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Installation\Config\Edit\Meta\Collection\DescriptorCollectionBuilder;
use Sam\Installation\Config\Edit\Meta\OptionHelperAwareTrait;
use Sam\Installation\Config\Edit\Validate\Value\ConfigValueValidator;
use Sam\Installation\Config\Edit\Validate\Value\ConfigValueValidatorCreateTrait;
use Sam\Installation\Config\Edit\Validate\Value\Special\Base\SpecialComplexValidatorBase;

/**
 * Validator for *.local configuration files
 *
 * Class LocalConfigValidator
 * @package Sam\Installation\Config
 */
class LocalConfigValidator extends CustomizableClass
{
    use ConfigValueValidatorCreateTrait;
    use OptionHelperAwareTrait;
    use ResultStatusCollectorAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $configName
     * @return bool
     */
    public function validate(string $configName): bool
    {
        $descriptorCollection = DescriptorCollectionBuilder::new()->build($configName);
        $descriptors = $descriptorCollection->toArray();
        foreach ($descriptorCollection->toArray() as $optionKey => $descriptor) {
            $value = $descriptor->getActualValue();
            $constraints = $descriptor->getConstraints();
            $hasSpecialComplexConstraint = array_key_exists(Constants\Installation::C_SPECIAL_COMPLEX, $constraints);
            if ($hasSpecialComplexConstraint) {
                /** @var SpecialComplexValidatorBase $class */
                $class = $constraints[Constants\Installation::C_SPECIAL_COMPLEX];
                /** @var SpecialComplexValidatorBase $specialComplexValidator */
                /** @noinspection VariableFunctionsUsageInspection */
                $specialComplexValidator = call_user_func([$class, 'new']);
                if (!$specialComplexValidator->validateForLoad($descriptors)) {
                    $errorStatuses = $specialComplexValidator->errorStatuses();
                    $this->processValidationErrors($optionKey, $value, $errorStatuses);
                }
            } else {
                $validator = $this->createValidator();
                if (!$validator->validate($value, $constraints)) {
                    $this->processValidationErrors($optionKey, $value, $validator->errorStatuses());
                }
            }
        }
        return !$this->getResultStatusCollector()->hasError();
    }

    /**
     * @return ResultStatus[]
     */
    public function errorStatuses(): array
    {
        return $this->getResultStatusCollector()->getErrorStatuses();
    }

    /**
     * @return array
     */
    public function errorMessages(): array
    {
        return $this->getResultStatusCollector()->getErrorMessages();
    }

    /**
     * Build array with error validated keys and setup them to $this->validationErrors.
     * @param string $optionKey flat option key
     * @param mixed $value
     * @param ResultStatus[] $errors
     * @return static
     */
    private function processValidationErrors(string $optionKey, mixed $value, array $errors): static
    {
        foreach ($errors as $error) {
            $this->getResultStatusCollector()->addError(
                $error->getCode(),
                $error->getMessage(),
                [
                    'option' => $optionKey,
                    'value' => $value
                ]
            );
        }

        return $this;
    }

    /**
     * @return ConfigValueValidator
     */
    private function createValidator(): ConfigValueValidator
    {
        return $this->createConfigValueValidator()->enableInputValueValidation(false);
    }
}
