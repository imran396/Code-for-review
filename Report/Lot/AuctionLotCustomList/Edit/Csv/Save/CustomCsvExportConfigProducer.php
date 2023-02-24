<?php
/**
 * SAM-813: Custom CSV export
 * SAM-6546: Refactor "Custom CSV Export" report management logic
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct. 04, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Lot\AuctionLotCustomList\Edit\Csv\Save;


use CustomCsvExportConfig;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Report\Lot\AuctionLotCustomList\Edit\Csv\Load\CustomCsvExportConfigLoaderAwareTrait;
use Sam\Report\Lot\AuctionLotCustomList\Edit\Csv\Load\CustomCsvExportDataLoaderAwareTrait;
use Sam\Storage\WriteRepository\Entity\CustomCsvExportConfig\CustomCsvExportConfigWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\CustomCsvExportData\CustomCsvExportDataWriteRepositoryAwareTrait;

/**
 * Class CustomCsvExportConfigProducer
 * @package Sam\Report\Lot\AuctionLotCustomList\Edit\Csv\Save
 */
class CustomCsvExportConfigProducer extends CustomizableClass
{
    use CustomCsvExportConfigLoaderAwareTrait;
    use CustomCsvExportConfigWriteRepositoryAwareTrait;
    use CustomCsvExportDataLoaderAwareTrait;
    use CustomCsvExportDataWriteRepositoryAwareTrait;
    use EntityFactoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function produce(CustomCsvExportConfigProduceCommand $command): int
    {
        $config = $this->loadConfigOrCreate($command->configId);
        if ($command->name) {
            $config->Name = $command->name;
        }
        $config->Active = true;
        $config->ImageWebLinks = $command->isImageWebLinks;
        $config->ImageSeparateColumns = $command->imageSeparateColumnsQty;
        $config->CategoriesSeparateColumns = $command->categoriesSeparateColumnsQty;
        $this->getCustomCsvExportConfigWriteRepository()->saveWithModifier($config, $command->editorUserId);
        $this->updateFieldConfigs($config->Id, $command->fieldConfigs, $command->editorUserId);

        return $config->Id;
    }

    /**
     * @param int $configId
     * @param array $fieldConfigs
     * @param int $editorUserId
     */
    protected function updateFieldConfigs(int $configId, array $fieldConfigs, int $editorUserId): void
    {
        $oldDataArray = $this->getCustomCsvExportDataLoader()->loadEntities($configId);
        foreach ($fieldConfigs as $index => $fieldConfig) {
            $customCsvExportData = null;
            if (!empty($oldDataArray)) {
                $customCsvExportData = array_shift($oldDataArray);
            }

            if (!$customCsvExportData) {
                $customCsvExportData = $this->createEntityFactory()->customCsvExportData();
                $customCsvExportData->ConfigId = $configId;
            }
            $customCsvExportData->FieldIndex = $index;
            $customCsvExportData->FieldName = $fieldConfig['name'];
            $customCsvExportData->FieldOrder = $fieldConfig['order'];
            $customCsvExportData->Active = true;
            $this->getCustomCsvExportDataWriteRepository()->saveWithModifier($customCsvExportData, $editorUserId);
        }
        if (!empty($oldDataArray)) {
            foreach ($oldDataArray as $customCsvExportData) {
                $customCsvExportData->Active = false;
                $this->getCustomCsvExportDataWriteRepository()->saveWithModifier($customCsvExportData, $editorUserId);
            }
        }
        unset($oldDataArray, $customCsvExportData);
    }

    /**
     * @param int|null $configId
     * @return CustomCsvExportConfig
     */
    protected function loadConfigOrCreate(?int $configId): CustomCsvExportConfig
    {
        if ($configId) {
            $config = $this->getCustomCsvExportConfigLoader()->load($configId);
        } else {
            $config = $this->createEntityFactory()->customCsvExportConfig();
        }
        return $config;
    }
}
