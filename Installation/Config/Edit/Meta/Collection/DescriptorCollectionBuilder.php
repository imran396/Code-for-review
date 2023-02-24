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

namespace Sam\Installation\Config\Edit\Meta\Collection;

use Sam\Core\Service\CustomizableClass;
use RecursiveArrayIterator;
use Sam\Core\Constants;
use Sam\Installation\Config\Edit\Load\FileContentLoaderCreateTrait;
use Sam\Installation\Config\Edit\Meta\Constraint\DescriptorAdditionalConstraintsBuilder;
use Sam\Installation\Config\Edit\Meta\Constraint\DescriptorConstraintsBuilderHelper;
use Sam\Installation\Config\Edit\Meta\Descriptor\Descriptor;
use Sam\Installation\Config\Edit\Meta\Descriptor\DescriptorBuilder;
use Sam\Installation\Config\Edit\Meta\OptionHelperAwareTrait;

/**
 * Class DescriptorCollectionBuilder
 *
 * Contains logic that responsive for glue, merge
 * and building global-local-meta config arrays. And store all built data to DescriptorCollection object.
 *
 * @package Sam\Installation\Config
 */
class DescriptorCollectionBuilder extends CustomizableClass
{
    use FileContentLoaderCreateTrait;
    use OptionHelperAwareTrait;

    /**
     * Temporary buffer for storage data, when building config options array with flat keys.
     * @var array
     */
    protected array $bufferForConfigOptions = [];

    /**
     * Temporary buffer for storage data, when building meta Descriptors for config options with flat keys.
     * @var array
     */
    protected array $bufferForDescriptors = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Build and fill DescriptorCollection
     * @param string $configName
     * @return DescriptorCollection
     */
    public function build(string $configName): DescriptorCollection
    {
        [$globalOptions, $localOptions] = $this->buildConfigOptions($configName);

        $metaOptions = $this->createFileContentLoader()->loadMetaOptions($configName);
        $multiDimGlobalOptions = $this->getOptionHelper()->transformFlatKeyToMultiDimKeyOptions($globalOptions);
        $globalConfigDescriptors = $this->buildGlobalConfigMetaDescriptors(
            new RecursiveArrayIterator($multiDimGlobalOptions),
            $metaOptions,
            $globalOptions,
            $localOptions
        );

        $missingDescriptors = $this->buildMissingDescriptors($globalConfigDescriptors, $localOptions);
        $descriptors = array_merge($globalConfigDescriptors, $missingDescriptors);
        ksort($descriptors);

        $logMessage = count($globalOptions) && count($descriptors)
            ? 'Descriptor collection successfully created.'
            : 'Descriptor collection build failed.';
        log_info($logMessage);

        $descriptorCollection = DescriptorCollection::new()->construct($configName, $descriptors);
        return $descriptorCollection;
    }

    /**
     * @param string $configName
     * @return array
     */
    protected function buildConfigOptions(string $configName): array
    {
        $globalOptions = $localOptions = [];
        $fileContentLoader = $this->createFileContentLoader();
        $metaOptions = $fileContentLoader->loadMetaOptions($configName);
        $globalOptionsMultiDim = $fileContentLoader->loadGlobalOptionsMultiDim($configName);
        if (isset($globalOptionsMultiDim)) {
            $iteratorForMultiDimConfigOptions = new RecursiveArrayIterator($globalOptionsMultiDim);
            $globalOptions = $this->traverseStructure($iteratorForMultiDimConfigOptions, $metaOptions);
        }
        $localOptionsMultiDim = $fileContentLoader->loadLocalOptionsMultiDim($configName);
        if (isset($localOptionsMultiDim)) {
            $iteratorForMultiDimConfigOptions = new RecursiveArrayIterator($localOptionsMultiDim);
            $localOptions = $this->traverseStructure($iteratorForMultiDimConfigOptions, $metaOptions);
        }
        return [$globalOptions, $localOptions];
    }

    /**
     * Complete meta descriptors array with information fetched from actual config data
     * @param RecursiveArrayIterator $iterator
     * @param array $metaOptions
     * @param array $globalOptions
     * @param array $localOptions
     * @param bool $clearBuffer
     * @param array $paths
     * @param array $branchDescriptorOut
     *
     * @return array with instances of Descriptor
     */
    protected function buildGlobalConfigMetaDescriptors(
        RecursiveArrayIterator $iterator,
        array $metaOptions,
        array $globalOptions,
        array $localOptions,
        bool $clearBuffer = true,
        array $paths = [],
        array $branchDescriptorOut = []
    ): array {
        if ($clearBuffer) {
            $this->bufferForDescriptors = [];
        }
        $descriptorBuilder = DescriptorBuilder::new()
            ->setGlobalOptions($globalOptions)
            ->setLocalOptions($localOptions);

        while ($iterator->valid()) {
            $hasChild = $iterator->hasChildren();
            $countChild = $hasChild ? count($iterator->getChildren()) : 0;
            $paths[] = $iterator->key();
            $optionKey = implode(Constants\Installation::DELIMITER_GENERAL_OPTION_KEY, $paths);
            $metaOptionKey = implode(Constants\Installation::DELIMITER_META_OPTION_KEY, $paths);

            if (isset($metaOptions[$metaOptionKey])) {
                $unfinishedMetaDescriptor = $descriptorBuilder->buildFromMetaArray([$metaOptionKey => $metaOptions[$metaOptionKey]]);
                $unfinishedMetaDescriptor = $descriptorBuilder->addOptionValues($unfinishedMetaDescriptor);
                $branchDescriptorOut[$optionKey] = $this->finishMetaDescriptor($unfinishedMetaDescriptor, $iterator->current());
            } elseif ($hasChild && $countChild) {
                $this->buildGlobalConfigMetaDescriptors(
                    $iterator->getChildren(),
                    $metaOptions,
                    $globalOptions,
                    $localOptions,
                    false,
                    $paths,
                    $branchDescriptorOut
                );
            } else {
                $unfinishedMetaDescriptor = $descriptorBuilder->buildFromMetaArray([$metaOptionKey => []]);
                $unfinishedMetaDescriptor = $descriptorBuilder->addOptionValues($unfinishedMetaDescriptor);
                $branchDescriptorOut[$optionKey] = $this->finishMetaDescriptor($unfinishedMetaDescriptor, $iterator->current());
            }

            array_pop($paths);
            $iterator->next();
        }

        $this->bufferForDescriptors = array_merge($branchDescriptorOut, $this->bufferForDescriptors);
        ksort($this->bufferForDescriptors);
        return $this->bufferForDescriptors;
    }

    /**
     * Finish build meta Descriptor.
     * @param Descriptor $descriptor
     * @param mixed $value
     * @return Descriptor
     */
    protected function finishMetaDescriptor(Descriptor $descriptor, mixed $value): Descriptor
    {
        $readyDescriptor = $descriptor;
        if ($descriptor->getType() === null) {
            $readyDescriptor = DescriptorBuilder::new()->setTypeForDescriptorFromConfigValue($readyDescriptor, $value);
            $readyDescriptor = DescriptorConstraintsBuilderHelper::new()->addConstraintsForDataType($readyDescriptor);
        }
        $readyDescriptor = DescriptorAdditionalConstraintsBuilder::new()->addAdditionalConstraints($readyDescriptor);
        return $readyDescriptor;
    }

    /**
     * @param Descriptor[] $globalConfigMetaDescriptors
     * @param array $localOptions
     * @return Descriptor[]|array
     */
    private function buildMissingDescriptors(array $globalConfigMetaDescriptors, array $localOptions): array
    {
        $descriptors = [];
        foreach ($localOptions as $optionKey => $value) {
            if (!array_key_exists($optionKey, $globalConfigMetaDescriptors)) {
                $descriptors[$optionKey] = DescriptorBuilder::new()
                    ->buildForMissedOption($optionKey, $value);
            }
        }
        return $descriptors;
    }

    /**
     * @param RecursiveArrayIterator $iterator
     * @param array $metaOptions
     * @param bool $emptyBuffer
     * @param array $paths
     * @param array $branchOut
     * @return array
     */
    private function traverseStructure(
        RecursiveArrayIterator $iterator,
        array $metaOptions,
        bool $emptyBuffer = true,
        array $paths = [],
        array $branchOut = []
    ): array {
        if ($emptyBuffer) {
            $this->bufferForConfigOptions = [];
        }
        $uniqueDataTypes = [Constants\Type::T_ARRAY, Constants\Installation::T_STRUCT_ARRAY];
        while ($iterator->valid()) {
            $hasChild = $iterator->hasChildren();
            $countChild = $hasChild ? count($iterator->getChildren()) : 0;
            $key = $iterator->key();
            $paths[] = $key;
            $optionKey = implode(Constants\Installation::DELIMITER_GENERAL_OPTION_KEY, $paths);
            $metaOptionKey = implode(Constants\Installation::DELIMITER_META_OPTION_KEY, $paths);
            if (
                !$hasChild
                || (
                    isset($metaOptions[$metaOptionKey][Constants\Installation::META_ATTR_TYPE])
                    && in_array($metaOptions[$metaOptionKey][Constants\Installation::META_ATTR_TYPE], $uniqueDataTypes, true)
                )
            ) {
                $branchOut[$optionKey] = $iterator->current();
            } elseif ($countChild) {
                $this->traverseStructure($iterator->getChildren(), $metaOptions, false, $paths, $branchOut);
            } else {
                $branchOut[$optionKey] = null;
            }
            array_pop($paths);
            $iterator->next();
        }
        $this->bufferForConfigOptions = array_merge($branchOut, $this->bufferForConfigOptions);
        ksort($this->bufferForConfigOptions);
        return $this->bufferForConfigOptions;
    }
}
