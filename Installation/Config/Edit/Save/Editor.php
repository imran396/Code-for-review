<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           28/04/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Save;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Edit\Delete\Deleter;
use Sam\Installation\Config\Edit\Meta\Collection\DescriptorCollection;
use Sam\Installation\Config\Edit\Meta\Collection\DescriptorCollectionAwareTrait;
use Sam\Installation\Config\Edit\Meta\OptionHelperAwareTrait;

/**
 * Class Editor
 * @package Sam\Installation\Config
 * @author: Yura Vakulenko
 *
 * Provide logic for edit configuration file from web-interface POST request.
 */
class Editor extends CustomizableClass
{
    use DescriptorCollectionAwareTrait;
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
     * Update content of local config file for current config name.
     * @param ReadyForPublishData $publishData
     * @param DescriptorCollection $descriptorCollection
     * @return bool
     */
    public function updateConfig(ReadyForPublishData $publishData, DescriptorCollection $descriptorCollection): bool
    {
        $this->setDescriptorCollection($descriptorCollection);
        $removeData = $publishData->getRemove();
        $updateData = $publishData->getUpdate();
        $successRemoved = count($removeData) ? $this->processRemove($removeData) : true;
        $successUpdated = count($updateData) ? $this->processUpdate($updateData) : true;

        $success = false;
        if ($successUpdated && $successRemoved) {
            $success = true;
        }

        return $success;
    }

    /**
     * @param array $data one dimension array with flat option keys.
     * @return bool
     */
    protected function processUpdate(array $data): bool
    {
        $publishData = $this->getOptionHelper()->transformFlatKeyToMultiDimKeyOptions($data);
        $success = Publisher::new()->publishToLocalConfig(
            Publisher::ACTION_UPDATE,
            $publishData,
            $this->getDescriptorCollection()
        );
        return $success;
    }

    /**
     * @param array $data one dimension array with flat option keys.
     * @return bool
     */
    protected function processRemove(array $data): bool
    {
        $success = Deleter::new()->deleteFromLocalConfigMultiValues($data, $this->getDescriptorCollection());
        return $success;
    }
}
