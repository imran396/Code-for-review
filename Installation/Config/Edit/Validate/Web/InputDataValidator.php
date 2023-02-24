<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           03/06/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Validate\Web;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Installation\Config\Edit\Meta\Collection\DescriptorCollection;
use Sam\Installation\Config\Edit\Meta\Collection\DescriptorCollectionAwareTrait;
use Sam\Installation\Config\Edit\Validate\Value\ConfigValueValidatorCreateTrait;
use Sam\Installation\Config\Edit\Validate\Value\Special\Base\SpecialComplexValidatorBase;

/**
 * Class InputDataValidator
 * Validate input POST data from web-interface form
 *
 * @package Sam\Installation\Config
 * @author: Yura Vakulenko
 */
class InputDataValidator extends CustomizableClass
{
    use ConfigValueValidatorCreateTrait;
    use DescriptorCollectionAwareTrait;

    /**
     * @var ValidatedData[]
     */
    protected array $validatedData = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Validate input data
     * @param array $postData
     * @param DescriptorCollection $descriptorCollection
     * @return bool
     */
    public function validate(array $postData, DescriptorCollection $descriptorCollection): bool
    {
        $this->setDescriptorCollection($descriptorCollection);
        $this->validateEditableOptions($postData);
        foreach ($this->validatedData as $validatedData) {
            if (!empty($validatedData->getValidationResults())) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return ValidatedData[]
     */
    public function getValidatedData(): array
    {
        return $this->validatedData;
    }

    /**
     * Validate all editable options, including required fields checking, if input data for them is absent
     * @param array $data
     * @return static
     */
    protected function validateEditableOptions(array $data): static
    {
        $editableDescriptors = $this->getDescriptorCollection()->getEditableDescriptors();
        foreach ($editableDescriptors as $optionKey => $descriptor) {
            $errorMessages = [];
            $value = $data[$optionKey] ?? null;
            $constraints = $descriptor->getConstraints();
            $hasSpecialComplexConstraint = array_key_exists(Constants\Installation::C_SPECIAL_COMPLEX, $constraints);
            if ($hasSpecialComplexConstraint) {
                /** @var SpecialComplexValidatorBase $class */
                $class = $constraints[Constants\Installation::C_SPECIAL_COMPLEX];
                /** @var SpecialComplexValidatorBase $specialComplexValidator */
                $specialComplexValidator = call_user_func([$class, 'new']);
                if (!$specialComplexValidator->validateForSave($editableDescriptors, $data)) {
                    $errorMessages = $specialComplexValidator->errorMessages();
                }
                $this->validatedData[$optionKey] = new ValidatedData($value, $errorMessages);
            } else {
                // Validate all filled input data, and required options if they are not filled
                $needValidation = array_key_exists(Constants\Installation::C_REQUIRED, $constraints)
                    ? true
                    : isset($data[$optionKey]);
                if ($needValidation) {
                    $valueValidator = $this->createConfigValueValidator();
                    if (!$valueValidator->validate($value, $constraints)) {
                        $errorMessages = $valueValidator->errorMessages();
                    }
                    $this->validatedData[$optionKey] = new ValidatedData($value, $errorMessages);
                }
            }
        }
        return $this;
    }
}
