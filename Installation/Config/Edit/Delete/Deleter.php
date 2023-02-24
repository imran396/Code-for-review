<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           24/05/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Delete;

use Sam\Core\Constants;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Edit\Delete\Internal\DataPreparerForPublisher;
use Sam\Installation\Config\Edit\Delete\Internal\Helpers\DeleterHelper;
use Sam\Installation\Config\Edit\Delete\Internal\Validate\ConfigOptionValidatorAwareTrait;
use Sam\Installation\Config\Edit\Meta\Collection\DescriptorCollection;
use Sam\Installation\Config\Edit\Save\Publisher;
use Sam\Installation\Config\Edit\Validate\Value\Special\Base\SpecialComplexValidatorBase;

/**
 * Class Deleter
 * @package Sam\Installation\Config
 * @author: Yura Vakulenko
 *
 * Provide logic for perform delete config values from local config file
 * for current config name.
 */
class Deleter extends CustomizableClass
{
    use ConfigOptionValidatorAwareTrait;
    use ResultStatusCollectorAwareTrait;

    // ResultStatusCollector statuses constants
    public const OK_DELETED = 1;

    public const ERROR_FAIL_DELETE = 2;
    public const ERROR_PROTECTED = 3;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return $this
     */
    public function initInstance(): static
    {
        $this->initResultStatusCollector();
        return $this;
    }

    /**
     * Delete single config value from local config file
     *
     * use Constants\Installation::DELIMITER_GENERAL_OPTION_KEY as config option path delimited
     * @param string $optionKey
     * @param DescriptorCollection $descriptorCollection
     * @return bool
     */
    public function deleteFromLocalConfig(string $optionKey, DescriptorCollection $descriptorCollection): bool
    {
        $success = false;
        $errorMessage = '';
        $resultStatusCollector = $this->getResultStatusCollector();
        $deleterHelper = DeleterHelper::new();

        $actualLocalOptions = $deleterHelper->getActualLocalOptions($descriptorCollection);
        if ($this->getConfigOptionValidator()->validateConfigKey($optionKey, $actualLocalOptions)) {
            $descriptor = $descriptorCollection->get($optionKey);
            $canDelete = $isDeletable = $deleterHelper->isDeletableOption($descriptor);

            $renderOptionKey = $deleterHelper->renderOptionKey($optionKey, $descriptorCollection->getConfigName());
            if ($isDeletable) {
                $constraints = $descriptor->getConstraints();
                $hasSpecialComplexConstraint = array_key_exists(Constants\Installation::C_SPECIAL_COMPLEX, $constraints);
                if ($hasSpecialComplexConstraint) {
                    /** @var SpecialComplexValidatorBase $class */
                    $class = $constraints[Constants\Installation::C_SPECIAL_COMPLEX];
                    /** @var SpecialComplexValidatorBase $specialComplexValidator */
                    /** @noinspection VariableFunctionsUsageInspection */
                    $specialComplexValidator = call_user_func([$class, 'new']);
                    if (!$specialComplexValidator->validateForDelete($descriptorCollection->toArray())) {
                        $errorMessage = implode("\n", $specialComplexValidator->errorMessages());
                        $canDelete = false;
                    }
                }

                if ($canDelete) {
                    $publishData = DataPreparerForPublisher::new()->prepare($optionKey, $actualLocalOptions);
                    // Publish data to local config file.
                    $success = Publisher::new()->publishToLocalConfig(
                        Publisher::ACTION_REMOVE,
                        $publishData,
                        $descriptorCollection
                    );
                    if ($success) {
                        $successMessage = sprintf('Config value %s successfully deleted. Local config values updated!', $renderOptionKey);
                        $resultStatusCollector->addSuccess(self::OK_DELETED, $successMessage);
                    }
                }
            }
            if (!$success) {
                if ($isDeletable) {
                    $resultStatusCollector->addError(self::ERROR_FAIL_DELETE, $errorMessage);
                } else {
                    $errorMessage = sprintf('Local %s configuration value is protected and can not be deleted!', $renderOptionKey);
                    $resultStatusCollector->addError(self::ERROR_PROTECTED, $errorMessage);
                }
            }
        }

        return $success;
    }

    /**
     * Delete multiple values from local config at one time.
     * @param array $optionKeys input one dimension with flat keys in array.
     * @param DescriptorCollection $descriptorCollection
     * @return bool
     */
    public function deleteFromLocalConfigMultiValues(array $optionKeys, DescriptorCollection $descriptorCollection): bool
    {
        $success = false;
        $deleted = [];
        foreach ($optionKeys ?: [] as $optionKey) {
            $deleted[$optionKey] = (int)$this->deleteFromLocalConfig($optionKey, $descriptorCollection);
        }
        $countsDeleted = array_count_values($deleted);
        if (
            isset($countsDeleted[1])
            && count($deleted) === $countsDeleted[1]
        ) {
            $success = true;
        }
        return $success;
    }

    /**
     * get ResultStatusCollector error messages
     * @return string[]
     */
    public function errorMessages(): array
    {
        $configOptionValidatorErrors = $this->getConfigOptionValidator()->errorMessages();
        $currentServiceErrors = $this->getResultStatusCollector()->getErrorMessages();
        $errorMessages = array_merge($configOptionValidatorErrors, $currentServiceErrors);
        return $errorMessages;
    }

    /**
     * get ResultStatusCollector success messages
     * @return string[]
     */
    public function successMessages(): array
    {
        return $this->getResultStatusCollector()->getSuccessMessages();
    }

    /**
     * Initialize ResultStatusCollector
     */
    protected function initResultStatusCollector(): void
    {
        // ResultStatusCollector default success messages for success codes
        $successMessages = [
            self::OK_DELETED => 'Successfully deleted!',
        ];
        // ResultStatusCollector default error messages for error codes
        $errorMessages = [
            self::ERROR_FAIL_DELETE => 'Configuration value not deleted. Local config not updated!',
            self::ERROR_PROTECTED => 'Configuration value is protected and can not be deleted!',
        ];
        $this->getResultStatusCollector()->construct($errorMessages, $successMessages);
    }
}
