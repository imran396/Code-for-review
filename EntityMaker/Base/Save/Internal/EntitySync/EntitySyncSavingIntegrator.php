<?php
/**
 * Glue service for integration of EntitySync creation/updating service logic with entity-maker producers.
 *
 * SAM-5015: Unite sync tables data scheme
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 15, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Base\Save\Internal\EntitySync;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Account\Save\AccountMakerProducer;
use Sam\EntityMaker\Auction\Save\AuctionMakerProducer;
use Sam\EntityMaker\AuctionLot\Save\AuctionLotMakerProducer;
use Sam\EntityMaker\Base\Save\BaseMakerProducer;
use Sam\EntityMaker\Base\Save\Exception\InvalidEntityMakerProducer;
use Sam\EntityMaker\Base\Save\Internal\EntitySync\Internal\Save\EntitySyncSaverCreateTrait;
use Sam\EntityMaker\Base\Save\Internal\EntitySync\Internal\Save\EntitySyncSavingInput;
use Sam\EntityMaker\Location\Save\LocationMakerProducer;
use Sam\EntityMaker\LotItem\Save\LotItemMakerProducer;
use Sam\EntityMaker\User\Save\UserMakerProducer;

/**
 * Class EntitySyncSavingIntegrator
 * @package Sam\EntityMaker\Base\Save\Internal\EntitySync
 */
class EntitySyncSavingIntegrator extends CustomizableClass
{
    use EntitySyncSaverCreateTrait;

    /** @var array<string, int> */
    protected const PRODUCER_TO_TYPE_MAP = [
        AccountMakerProducer::class => Constants\EntitySync::TYPE_ACCOUNT,
        AuctionMakerProducer::class => Constants\EntitySync::TYPE_AUCTION,
        AuctionLotMakerProducer::class => Constants\EntitySync::TYPE_AUCTION_LOT_ITEM,
        LocationMakerProducer::class => Constants\EntitySync::TYPE_LOCATION,
        LotItemMakerProducer::class => Constants\EntitySync::TYPE_LOT_ITEM,
        UserMakerProducer::class => Constants\EntitySync::TYPE_USER,
    ];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Integrate internal service of new EntitySync record creation into entity-maker producers.
     * @param AccountMakerProducer|AuctionMakerProducer|AuctionLotMakerProducer|LocationMakerProducer|LotItemMakerProducer|UserMakerProducer $entityMakerProducer
     */
    public function create(BaseMakerProducer $entityMakerProducer): void
    {
        $this->save($entityMakerProducer, true);
    }

    /**
     * Integrate internal service of existing EntitySync record updating into entity-maker producers.
     * @param AccountMakerProducer|AuctionMakerProducer|AuctionLotMakerProducer|LocationMakerProducer|LotItemMakerProducer|UserMakerProducer $entityMakerProducer
     */
    public function update(BaseMakerProducer $entityMakerProducer): void
    {
        $this->save($entityMakerProducer, false);
    }

    /**
     * Integrate internal service into entity-maker producers
     * with the intention to create new or update existing EntitySync record.
     * @param AccountMakerProducer|AuctionMakerProducer|AuctionLotMakerProducer|LocationMakerProducer|LotItemMakerProducer|UserMakerProducer $entityMakerProducer
     * @param bool $isCreate
     */
    protected function save(BaseMakerProducer $entityMakerProducer, bool $isCreate): void
    {
        $type = $this->detectEntityType($entityMakerProducer);
        $inputDto = $entityMakerProducer->getInputDto();
        $configDto = $entityMakerProducer->getConfigDto();
        $input = EntitySyncSavingInput::new()->construct(
            (string)$inputDto->syncKey,
            Cast::toInt($inputDto->syncNamespaceId),
            Cast::toInt($inputDto->id),
            $type,
            $configDto->editorUserId
        );

        if ($isCreate) {
            $this->createEntitySyncSaver()->create($input);
            return;
        }

        $this->createEntitySyncSaver()->update($input);
    }

    /**
     * Detect the value of EntitySync->EntityType by class of entity-maker producer.
     * Must check with help of instanceof, because it supports inheritance.
     * @param BaseMakerProducer $entityMakerProducer
     * @return int
     */
    protected function detectEntityType(BaseMakerProducer $entityMakerProducer): int
    {
        foreach (self::PRODUCER_TO_TYPE_MAP as $class => $type) {
            if ($entityMakerProducer instanceof $class) {
                return $type;
            }
        }
        throw InvalidEntityMakerProducer::withClassOf($entityMakerProducer);
    }
}
