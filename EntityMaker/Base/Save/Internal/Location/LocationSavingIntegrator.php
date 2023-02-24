<?php
/**
 * Glue service for integration of Location creation/updating service logic with entity-maker producers.
 * SAM-10360: Decouple location validation and saving logic from parent classes
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 31, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Base\Save\Internal\Location;

use Auction;
use Location;
use LotItem;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Auction\Save\AuctionMakerProducer;
use Sam\EntityMaker\Base\Save\BaseMakerProducer;
use Sam\EntityMaker\Location\Dto\LocationMakerConfigDto;
use Sam\EntityMaker\Location\Dto\LocationMakerDtoFactory;
use Sam\EntityMaker\Location\Dto\LocationMakerInputDto;
use Sam\EntityMaker\Location\Save\LocationMakerProducer;
use Sam\EntityMaker\LotItem\Save\LotItemMakerProducer;
use Sam\EntityMaker\User\Save\UserMakerProducer;
use Sam\Location\Delete\LocationDeleterAwareTrait;
use Sam\Storage\WriteRepository\Entity\Auction\AuctionWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\LotItem\LotItemWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\UserInfo\UserInfoWriteRepositoryAwareTrait;
use UserInfo;

/**
 * Class LocationSavingIntegrator
 * @package Sam\EntityMaker\Base\Save\Internal\Location
 */
class LocationSavingIntegrator extends CustomizableClass
{
    use AuctionWriteRepositoryAwareTrait;
    use LocationDeleterAwareTrait;
    use LotItemWriteRepositoryAwareTrait;
    use UserInfoWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param BaseMakerProducer $entityMakerProducer
     * @param string $specificDtoField
     * @param array $commonDtoFields
     * @param int $entityType
     * @param Auction|LotItem|UserInfo $entity
     * @param string $commonDbField
     */
    public function removeExcessCommonOrSpecificLocation(
        BaseMakerProducer $entityMakerProducer,
        string $specificDtoField,
        array $commonDtoFields,
        int $entityType,
        Auction|LotItem|UserInfo $entity,
        string $commonDbField
    ): void {
        $inputDto = $entityMakerProducer->getInputDto();
        $configDto = $entityMakerProducer->getConfigDto();

        // Specific location assigned - remove common location
        if (
            $inputDto->{$specificDtoField}
            && $entity->{$commonDbField}
        ) {
            $entity->{$commonDbField} = null;

            if ($entity instanceof Auction) {
                $this->getAuctionWriteRepository()->saveWithModifier($entity, $configDto->editorUserId);
            } elseif ($entity instanceof LotItem) {
                $this->getLotItemWriteRepository()->saveWithModifier($entity, $configDto->editorUserId);
            } elseif ($entity instanceof UserInfo) {
                $this->getUserInfoWriteRepository()->saveWithModifier($entity, $configDto->editorUserId);
            }
        }
        // Common location assigned - remove specific location
        if (
            $inputDto->{$commonDtoFields[0]}
            || (isset($commonDtoFields[1]) ? $inputDto->{$commonDtoFields[1]} : false)
        ) {
            // User location is stored in user_info table
            $entityId = (get_class($entity) === UserInfo::class) ? $entity->UserId : $entity->Id;

            $this->getLocationDeleter()->deleteByTypeAndEntityId(
                $entityType,
                $entityId,
                $configDto->editorUserId
            );
        }
    }

    public function save(
        AuctionMakerProducer|LotItemMakerProducer|UserMakerProducer $entityMakerProducer,
        ?object $field,
        int $type,
        ?int $serviceAccountId = null
    ): ?Location {
        $inputDto = $entityMakerProducer->getInputDto();
        $configDto = $entityMakerProducer->getConfigDto();
        $serviceAccountId = $serviceAccountId ?: $configDto->serviceAccountId;

        if (
            !(array)$field
            || !array_filter((array)$field)
        ) {
            $this->getLocationDeleter()->deleteByTypeAndEntityId(
                $type,
                $inputDto->id,
                $configDto->editorUserId
            );
            return null;
        }

        /**
         * @var LocationMakerInputDto $locationInputDto
         * @var LocationMakerConfigDto $locationConfigDto
         */
        [$locationInputDto, $locationConfigDto] = LocationMakerDtoFactory::new()
            ->createDtos($configDto->mode, $configDto->editorUserId, $serviceAccountId, $configDto->systemAccountId);
        $locationInputDto->setArray((array)$field);
        $locationConfigDto->entityId = Cast::toInt($inputDto->id);
        $locationConfigDto->entityType = $type;

        $locationConfigDto->enableValidStatus(true);
        $producer = LocationMakerProducer::new()->construct($locationInputDto, $locationConfigDto)->produce();
        return $producer->resultLocation();
    }
}
