<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Июнь 03, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidp ath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Save;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Installation\Config\Edit\Meta\Collection\DescriptorCollection;
use Sam\Installation\Config\Edit\Meta\Collection\DescriptorCollectionAwareTrait;
use Sam\Installation\Config\Edit\Meta\Descriptor\MissingDescriptor;
use Sam\Installation\Config\Edit\Transform\ParsingHelper;

/**
 * Class ReadyForPublishDataBuilder
 * @package Sam\Installation\Config
 */
class ReadyForPublishDataBuilder extends CustomizableClass
{
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
     * Build ready for publish data for used in Editor class
     * @param array $readyForPublishData array with flat key options
     * @param DescriptorCollection $descriptorCollection
     * @return ReadyForPublishData
     */
    public function build(array $readyForPublishData, DescriptorCollection $descriptorCollection): ReadyForPublishData
    {
        $this->setDescriptorCollection($descriptorCollection);
        $output = [];
        foreach ($readyForPublishData ?: [] as $optionKey => $value) {
            $normalizedValue = $this->normalizeValue($value, $optionKey);
            $descriptor = $descriptorCollection->get($optionKey);
            $normalizedValue = $this->leadToTypeAndFilter($normalizedValue, $descriptor->getType());
            $output[$optionKey] = $normalizedValue;
        }
        $output = $this->prepareForPublish($output);
        return $output;
    }

    /**
     * Prepare data for publish.
     * Group input data by 2 groups: for update or delete local config values. Value will be put to 'delete' group,
     * if it same with global option value.
     * @param array $data one dimensional data array with flat options keys.
     * @return ReadyForPublishData
     */
    protected function prepareForPublish(array $data): ReadyForPublishData
    {
        $updateData = $removeData = $sameAsGlobalData = [];
        if (count($data)) {
            $validTypes = [Constants\Type::T_INTEGER, Constants\Type::T_FLOAT, Constants\Type::T_STRING];

            foreach ($data as $optionKey => $value) {
                $descriptor = $this->getDescriptorCollection()->get($optionKey);
                $hasLocalValue = $descriptor->hasLocalValue();

                if (
                    is_string($value)
                    && $descriptor->getType() === Constants\Type::T_ARRAY
                ) {
                    $value = ParsingHelper::new()->buildArrayFromString($value);
                }

                // if POST and Global config values are different
                if (!$descriptor instanceof MissingDescriptor) {
                    $valueGlobal = $descriptor->getGlobalValue();

                    // if $value is an array
                    if (
                        is_array($value)
                        && is_array($valueGlobal)
                    ) {
                        if (count($valueGlobal) !== count($value)) {
                            $updateData[$optionKey] = $value;
                        } else {
                            $sameAsFromGlobal = false;
                            $c = 0;
                            foreach ($value as $vItem) {
                                if (in_array($vItem, $valueGlobal, true)) {
                                    $c++;
                                }
                            }
                            if ($c === count($valueGlobal)) {
                                $sameAsFromGlobal = true;
                            }

                            if (!$sameAsFromGlobal) {
                                $updateData[$optionKey] = $value;
                            } elseif ($hasLocalValue) {
                                $removeData[] = $optionKey;
                            } else {
                                $sameAsGlobalData[$optionKey] = $value;
                            }
                        }
                    } elseif (gettype($valueGlobal) === gettype($value)) {
                        if ($valueGlobal !== $value) {
                            $updateData[$optionKey] = $value;
                        } elseif ($hasLocalValue) {
                            $removeData[] = $optionKey;
                        } else {
                            $sameAsGlobalData[$optionKey] = $value;
                        }
                    } elseif ($valueGlobal === null) {
                        if (in_array(gettype($value), $validTypes, true)) {
                            $updateData[$optionKey] = $value;
                        } elseif (empty($value) && $hasLocalValue) {
                            $removeData[] = $optionKey;
                        } else {
                            $sameAsGlobalData[$optionKey] = $value;
                        }
                    }
                }
            }
        }

        $output = new ReadyForPublishData($updateData, $removeData, $sameAsGlobalData);
        return $output;
    }

    /**
     * Normalize input value, eg. transform $value from string to array
     * @param mixed $value
     * @param string $optionKey
     * @return mixed
     */
    protected function normalizeValue(mixed $value, string $optionKey): mixed
    {
        $output = $value;
        $descriptor = $this->getDescriptorCollection()->get($optionKey);
        if (
            is_string($value)
            && $descriptor->getEditComponent() !== Constants\Installation::ECOM_MULTISELECT
            && $descriptor->getType() === Constants\Type::T_ARRAY
        ) {
            $output = ParsingHelper::new()->buildArrayFromString($value);
        }

        return $output;
    }

    /**
     * @param mixed $value
     * @param string $type
     * @return int|float|string|array|bool|null
     */
    protected function leadToTypeAndFilter(mixed $value, string $type): null|int|float|string|array|bool
    {
        $output = match ($type) {
            Constants\Type::T_NULL => empty($value) ? null : $value,
            Constants\Type::T_INTEGER => Cast::toInt($value),
            Constants\Type::T_FLOAT => Cast::toFloat($value),
            Constants\Type::T_STRING => $value,
            Constants\Type::T_ARRAY => is_array($value) ? $value : [],
            Constants\Type::T_BOOL => (bool)$value,
            default => '',
        };

        return $output;
    }
}
