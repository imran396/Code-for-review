<?php
/**
 * Class for producing of Location entity
 *
 * SAM-10273: Entity locations: Implementation (Dev)
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 2, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Location\Save;

use Exception;
use Location;
use Sam\Core\Address\Render\AddressRenderer;
use Sam\EntityMaker\Location\Dto\LocationMakerDtoHelperAwareTrait;
use Sam\Location\Image\Save\LocationImageProducer;
use Sam\Location\Load\Exception\CouldNotFindLocation;
use Sam\Location\Load\LocationLoaderAwareTrait;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Date\CurrentDateTrait;
use Sam\EntityMaker\Location\Dto\LocationMakerConfigDto;
use Sam\EntityMaker\Location\Dto\LocationMakerInputDto;
use Sam\EntityMaker\Base\Save\BaseMakerProducer;
use Sam\EntityMaker\Base\Save\Internal\EntitySync\EntitySyncSavingIntegratorCreateTrait;
use Sam\Storage\WriteRepository\Entity\Location\LocationWriteRepositoryAwareTrait;

/**
 * @method LocationMakerInputDto getInputDto()
 * @method LocationMakerConfigDto getConfigDto()
 */
class LocationMakerProducer extends BaseMakerProducer
{
    use CurrentDateTrait;
    use DbConnectionTrait;
    use EntityFactoryCreateTrait;
    use EntitySyncSavingIntegratorCreateTrait;
    use LocationLoaderAwareTrait;
    use LocationMakerDtoHelperAwareTrait;
    use LocationWriteRepositoryAwareTrait;

    protected ?Location $resultLocation = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param LocationMakerInputDto $inputDto
     * @param LocationMakerConfigDto $configDto
     * @return static
     */
    public function construct(
        LocationMakerInputDto $inputDto,
        LocationMakerConfigDto $configDto
    ): static {
        $this->setInputDto($inputDto);
        $this->setConfigDto($configDto);
        $this->getLocationMakerDtoHelper()->construct($configDto->mode);
        return $this;
    }

    /**
     * Produce the location entity
     * @return static
     */
    public function produce(): static
    {
        $this->assertInputDto();
        $configDto = $this->getConfigDto();
        /** @var LocationMakerInputDto $inputDto */
        $inputDto = $this->getLocationMakerDtoHelper()->prepareValues($this->getInputDto(), $configDto);
        $this->setInputDto($inputDto);
        $this->assignValues();
        $this->atomicSave();
        return $this;
    }

    /**
     * Atomic persist data.
     * @throws Exception
     */
    protected function atomicSave(): void
    {
        $this->transactionBegin();
        try {
            $this->save();
        } catch (Exception $e) {
            log_errorBackTrace("Rollback transaction, because location save failed.");
            $this->transactionRollback();
            throw $e;
        }
        $this->transactionCommit();
    }

    /**
     * Persist data.
     */
    protected function save(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        $this->getLocationWriteRepository()->saveWithModifier($this->resultLocation, $configDto->editorUserId);
        $isNew = !$inputDto->id;
        if ($isNew) {
            $this->doPostCreate();
        } else {
            $this->doPostUpdate();
        }
    }

    /**
     * Get Location
     * @return Location
     */
    public function resultLocation(): Location
    {
        return $this->resultLocation;
    }

    /**
     * Assign location values from Dto object
     */
    public function assignValues(): void
    {
        $inputDto = $this->getInputDto();
        $location = $this->loadLocationOrCreate();
        $this->setIfAssign($location, 'address');
        $this->setIfAssign($location, 'city');
        $this->setIfAssign($location, 'county');
        $this->setIfAssign($location, 'logo');
        $this->setIfAssign($location, 'name');
        $this->setIfAssign($location, 'zip');
        if (isset($inputDto->country)) {
            $location->Country = AddressRenderer::new()->normalizeCountry($inputDto->country);
        }
        if (isset($inputDto->state)) {
            $location->State = AddressRenderer::new()->normalizeState($location->Country, $inputDto->state);
        }

        $configDto = $this->getConfigDto();
        if ($configDto->entityId) {
            $location->EntityId = $configDto->entityId;
        }
        if ($configDto->entityType) {
            $location->EntityType = $configDto->entityType;
        }

        $this->resultLocation = $location;
    }

    /**
     * Run necessary actions after the location was created
     *
     */
    protected function doPostCreate(): void
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        $inputDto->id = $this->resultLocation()->Id;

        $this->createEntitySyncSavingIntegrator()->create($this);

        if (isset($inputDto->logo)) {
            LocationImageProducer::new()->saveImageByLink(
                $this->resultLocation(),
                $configDto->serviceAccountId,
                $configDto->editorUserId
            );
        }
    }

    /**
     * Run necessary actions after the location was updated
     */
    protected function doPostUpdate(): void
    {
        $configDto = $this->getConfigDto();
        $this->createEntitySyncSavingIntegrator()->update($this);

        if (isset($this->getInputDto()->logo)) {
            LocationImageProducer::new()->saveImageByLink(
                $this->resultLocation(),
                $configDto->serviceAccountId,
                $configDto->editorUserId
            );
        }
    }

    /**
     * Load or create location depending on the location id
     * @return Location
     */
    private function loadLocationOrCreate(): Location
    {
        $inputDto = $this->getInputDto();
        $configDto = $this->getConfigDto();
        $locationId = (int)$inputDto->id;
        $entityId = (int)$configDto->entityId;
        $entityType = (int)$configDto->entityType;

        $location = null;
        if ($locationId) {
            $location = $this->getLocationLoader()->load($locationId);
            if (!$location) {
                throw CouldNotFindLocation::withId($locationId);
            }
        }
        if ($entityId) {
            $location = $this->getLocationLoader()->loadByTypeAndEntityId($entityType, $entityId);
        }
        if (!$location) {
            $location = $this->createEntityFactory()->location();
            $location->AccountId = $configDto->serviceAccountId;
            $location->Active = true;
        }
        return $location;
    }
}
