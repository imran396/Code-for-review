<?php
/**
 * SAM-5708: Local configuration management by CLI script
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 22, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Cli\Processor;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Installation\Config\Edit\Cli\Exception\CliApplicationException;
use Sam\Installation\Config\Edit\Meta\Collection\DescriptorCollection;
use Sam\Installation\Config\Edit\Meta\Collection\DescriptorCollectionAwareTrait;
use Sam\Installation\Config\Edit\Save\Editor;
use Sam\Installation\Config\Edit\Save\ReadyForPublishData;
use Sam\Installation\Config\Edit\Save\ReadyForPublishDataBuilder;
use Sam\Installation\Config\Edit\Validate\Value\ConfigValueValidatorCreateTrait;
use Sam\Installation\Config\Edit\Validate\Value\Special\Base\SpecialComplexValidatorBase;

/**
 * Responsible for validating and persisting config option values in the Console application
 *
 * Class ConfigUpdateProcessor
 * @package Sam\Installation\Config
 */
class ConfigUpdateProcessor extends CustomizableClass
{
    use ConfigValueValidatorCreateTrait;
    use DescriptorCollectionAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $optionKey
     * @param array|bool|string|null $value
     * @param bool $shouldValidate
     * @param DescriptorCollection $descriptorCollection
     * @return bool
     * @throws CliApplicationException
     */
    public function process(
        string $optionKey,
        array|bool|string|null $value,
        bool $shouldValidate,
        DescriptorCollection $descriptorCollection
    ): bool {
        $this->setDescriptorCollection($descriptorCollection);
        if ($this->validateOptionValue($optionKey, $value, $shouldValidate)) {
            return $this->saveOptionValue($optionKey, $value);
        }
        return false;
    }

    /**
     * @param string $optionKey
     * @param array|bool|string|null $value
     * @param bool $shouldValidate
     * @return bool
     * @throws CliApplicationException
     */
    private function validateOptionValue(string $optionKey, array|bool|string|null $value, bool $shouldValidate): bool
    {
        $validator = $this->createConfigValueValidator();
        if (!$shouldValidate) {
            $validator->enableInputValueValidation(false);
        }

        $constraints = $this->findConstraints($optionKey);
        $hasSpecialComplexConstraint = array_key_exists(Constants\Installation::C_SPECIAL_COMPLEX, $constraints);
        if ($hasSpecialComplexConstraint) {
            /** @var SpecialComplexValidatorBase $class */
            $class = $constraints[Constants\Installation::C_SPECIAL_COMPLEX];
            $specialComplexValidator = call_user_func([$class, 'new']);
            $editableDescriptors = $this->getDescriptorCollection()->getEditableDescriptors();
            $data = [$optionKey => $value];
            if (!$specialComplexValidator->validateForSave($editableDescriptors, $data)) {
                throw CliApplicationException::createFromMessages($specialComplexValidator->getErrorMessages());
            }
        } else {
            if (!$validator->validate($value, $this->findConstraints($optionKey))) {
                throw CliApplicationException::createFromMessages($validator->errorMessages());
            }
        }

        return true;
    }

    /**
     * @param string $optionKey
     * @return array
     */
    private function findConstraints(string $optionKey): array
    {
        return $this->getDescriptorCollection()
            ->get($optionKey)
            ->getConstraints();
    }

    /**
     * @param string $optionKey
     * @param array|bool|string|null $value
     * @return bool
     */
    private function saveOptionValue(string $optionKey, array|bool|string|null $value): bool
    {
        $publishData = $this->buildPublishData($optionKey, $value);
        $success = Editor::new()->updateConfig($publishData, $this->getDescriptorCollection());
        return $success;
    }

    /**
     * @param string $optionKey
     * @param array|bool|string|null $value
     * @return ReadyForPublishData
     */
    private function buildPublishData(string $optionKey, array|bool|string|null $value): ReadyForPublishData
    {
        return ReadyForPublishDataBuilder::new()->build([$optionKey => $value], $this->getDescriptorCollection());
    }
}
