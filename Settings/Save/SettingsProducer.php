<?php
/**
 * SAM-10551: Adjust SettingsManager for reading and caching data from different tables
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 26, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settings\Save;

use InvalidArgumentException;
use QBaseClass;
use Sam\Account\Main\MainAccountDetectorCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\Exception\CouldNotFindSettings;
use Sam\Settings\Repository\SettingsRepositoryProviderCreateTrait;
use Sam\Settings\Save\Internal\SettingDefaultValueApplierCreateTrait;
use Sam\Storage\Entity\Copy\EntityClonerCreateTrait;

/**
 * Class SettingsProducer
 * @package Sam\Settings\Save
 */
class SettingsProducer extends CustomizableClass
{
    use EntityClonerCreateTrait;
    use MainAccountDetectorCreateTrait;
    use SettingDefaultValueApplierCreateTrait;
    use SettingsRepositoryProviderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function createForAccount(int $accountId, int $editorUserId): void
    {
        $mainAccountId = $this->detectMainAccountId();
        $repositoryProvider = $this->createSettingsRepositoryProvider();
        $settingObjects = [];
        foreach ($repositoryProvider->getSettingsEntityClassNames() as $entityClassName) {
            $mainAccountSettingsEntity = $this->loadSettingsEntity($entityClassName, $mainAccountId);
            $settingObjects[$entityClassName] = $this->createEntityCloner()->cloneRecord($mainAccountSettingsEntity);
            $settingObjects[$entityClassName]->AccountId = $accountId; // @phpstan-ignore-line
        }
        $settingObjects = $this->createSettingDefaultValueApplier()->apply($settingObjects);
        $this->saveSettings($settingObjects, $editorUserId);
    }

    public function update(array $values, int $accountId, int $editorUserId): void
    {
        $settingsEntities = [];
        foreach ($values as $setting => $value) {
            $entityClassName = $this->getSettingEntityClassName($setting);
            $settingsEntities[$entityClassName] ??= $this->loadSettingsEntity($entityClassName, $accountId);
            $settingsEntities[$entityClassName]->{$setting} = $value;
        }
        $this->saveSettings($settingsEntities, $editorUserId);
    }

    /**
     * @template T of QBaseClass
     * @param class-string<T> $className
     * @param int $accountId
     * @return T
     */
    protected function loadSettingsEntity(string $className, int $accountId): QBaseClass
    {
        /** @var QBaseClass|null $entity */
        $entity = $this->createSettingsRepositoryProvider()
            ->getReadRepository($className)
            ->filterAccountId($accountId)
            ->loadEntity();
        if (!$entity) {
            throw CouldNotFindSettings::forClassNameWithAccountId($className, $accountId);
        }
        return $entity;
    }

    protected function saveSettings(array $settingsEntities, int $editorUserId): void
    {
        foreach ($settingsEntities as $settingsTable => $settingsEntity) {
            $repository = $this->createSettingsRepositoryProvider()->getWriteRepository($settingsTable);
            $repository->saveWithModifier($settingsEntity, $editorUserId);
        }
    }

    /**
     * Results with id of main account fetched from installation configuration.
     * @return int
     */
    protected function detectMainAccountId(): int
    {
        return $this->createMainAccountDetector()->id();
    }

    protected function getSettingEntityClassName(string $setting): string
    {
        $entityClassName = Constants\Setting::$typeMap[$setting]['entity'] ?? null;
        if (!$entityClassName) {
            throw new InvalidArgumentException("Unknown setting '{$setting}'");
        }
        return $entityClassName;
    }
}
