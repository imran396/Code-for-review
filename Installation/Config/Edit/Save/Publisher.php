<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Июнь 21, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Save;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Php\Prettier\PhpPrettier;
use Sam\File\Manage\LocalFileManagerCreateTrait;
use Sam\Installation\Config\Edit\Load\FileContentLoaderCreateTrait;
use Sam\Installation\Config\Edit\Meta\Collection\DescriptorCollection;
use Sam\Installation\Config\Edit\Meta\Collection\DescriptorCollectionAwareTrait;
use Sam\Installation\Config\Edit\Meta\OptionHelperAwareTrait;

/**
 * Class Publisher
 * Publish ready for publish data to Local config file for current config name.
 *
 * @package Sam\Installation\Config
 */
class Publisher extends CustomizableClass
{
    use DescriptorCollectionAwareTrait;
    use FileContentLoaderCreateTrait;
    use LocalFileManagerCreateTrait;
    use OptionHelperAwareTrait;

    public const ACTION_UPDATE = 'update';
    public const ACTION_REMOVE = 'remove';

    /**
     * ready for publish data used in $this->publishToLocalConfig, that we will store to our local configuration file.
     * @var array
     */
    protected array $publishData = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Publish data to local config file.
     * @param string $action Publish action ('update' or 'remove')
     * @param array $publishData
     * @param DescriptorCollection $descriptorCollection
     * @return bool
     */
    public function publishToLocalConfig(
        string $action,
        array $publishData,
        DescriptorCollection $descriptorCollection
    ): bool {
        $this->setPublishData($publishData);
        $this->setDescriptorCollection($descriptorCollection);

        $success = false;
        $preparedPublishData = $action === self::ACTION_REMOVE
            ? $publishData
            : $this->preparePublish();

        if ($preparedPublishData) {
            ksort($preparedPublishData, SORT_STRING);
            $content = var_export($preparedPublishData, true) . ';';
            $phpPrettier = new PhpPrettier();
            $content = $phpPrettier->transform($content);

            $content = '<?php return ' . $content . PHP_EOL;
            $configName = $this->getDescriptorCollection()->getConfigName();
            $localConfigFileRootPath = $this->createFileContentLoader()->getLocalConfigFileRootPath($configName);
            if (file_put_contents($localConfigFileRootPath, $content) !== false) {
                $success = true;
                log_debug(
                    'Local config file "' . $localConfigFileRootPath . '" updated by action "'
                    . $action . '" successfully!'
                );
                // make file writable by group and other, because installation user (e.g. "asdev") isn't in "apache" group,
                // but we still want to edit configuration files from shell
                $this->createLocalFileManager()->applyDefaultPermissions($localConfigFileRootPath);
            }
        } else {
            $success = true;
        }

        return $success;
    }

    /**
     * get ready for publish data
     * @return array
     */
    protected function getPublishData(): array
    {
        return $this->publishData;
    }

    /**
     * Setup publish data for using in $this->publishToLocalConfig()
     * @param array $publishData
     * @return static
     */
    protected function setPublishData(array $publishData): static
    {
        $this->publishData = $publishData;
        return $this;
    }

    /**
     * Replace recursively local options by publish data, or return local options if publish data is empty.
     * This action need for save in local options that values, that not exists in publish data if we edit configuration.
     * If we delete some config options from local configuration - this method will be ignored.
     * @return array
     */
    protected function preparePublish(): array
    {
        $publishData = $this->getPublishData();
        $configName = $this->getDescriptorCollection()->getConfigName();
        $localConfigFileContent = $this->createFileContentLoader()->loadLocalOptionsMultiDim($configName);
        if ($localConfigFileContent === null) {
            $output = $publishData;
        } elseif ($publishData) {
            $output = $this->prepareFinal($localConfigFileContent, $publishData);
        } else {
            $output = $localConfigFileContent;
        }

        return $output;
    }

    /**
     * @param array $localConfigFileContent multidimensional array.
     * @param array $publishData multidimensional array.
     * @return array
     */
    protected function prepareFinal(array $localConfigFileContent, array $publishData): array
    {
        $optionHelper = $this->getOptionHelper();
        $localOptions = $publishOptions = [];
        if (count($publishData)) {
            $descriptors = $this->getDescriptorCollection()->toArray();
            $multiDimDescriptors = $optionHelper->transformFlatKeyToMultiDimKeyOptions($descriptors);
            $publishOptions = $optionHelper->transformMultiDimKeyToFlatKeyArray($publishData, $multiDimDescriptors);
            if (count($localConfigFileContent)) {
                $localOptions = $optionHelper->transformMultiDimKeyToFlatKeyArray($localConfigFileContent, $multiDimDescriptors);
            }
        }
        if (count($publishOptions)) {
            $output = array_replace($localOptions, $publishOptions);
            $output = $optionHelper->transformFlatKeyToMultiDimKeyOptions($output);
        } else {
            $output = $localConfigFileContent;
        }

        return $output;
    }
}
