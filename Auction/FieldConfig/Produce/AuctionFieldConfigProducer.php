<?php
/**
 * SAM-9688: Ability to make Auction and Lot/Item fields required to have values: Implementation (Developer)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 01, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\FieldConfig\Produce;

use AuctionFieldConfig;
use Sam\Auction\FieldConfig\Delete\AuctionFieldConfigDeleterCreateTrait;
use Sam\Auction\FieldConfig\Internal\Load\AuctionFieldConfigLoaderCreateTrait;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\AuctionFieldConfig\AuctionFieldConfigWriteRepositoryAwareTrait;

/**
 * Class AuctionFieldConfigProducer
 * @package Sam\Auction\FieldConfig\Produce
 */
class AuctionFieldConfigProducer extends CustomizableClass
{
    use AuctionFieldConfigDeleterCreateTrait;
    use AuctionFieldConfigLoaderCreateTrait;
    use AuctionFieldConfigWriteRepositoryAwareTrait;
    use EntityFactoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Actualize and persist field configs in DB
     *
     * @param AuctionFieldConfigDto[] $fieldConfigDtos
     * @param int $accountId
     * @param int $editorUserid
     */
    public function produce(array $fieldConfigDtos, int $accountId, int $editorUserid): void
    {
        $fieldConfigIds = [];
        foreach ($fieldConfigDtos as $fieldConfigDto) {
            $fieldConfig = $this->createAuctionFieldConfigLoader()->loadByIndex($fieldConfigDto->key, $accountId);
            if (!$fieldConfig) {
                $fieldConfig = $this->makeFieldConfigEntity($fieldConfigDto->key, $accountId);
            }
            $fieldConfig->Required = $fieldConfigDto->required;
            $fieldConfig->Visible = $fieldConfigDto->visible;
            $fieldConfig->Order = $fieldConfigDto->order;
            $this->getAuctionFieldConfigWriteRepository()->saveWithModifier($fieldConfig, $editorUserid);
            $fieldConfigIds[] = $fieldConfig->Id;
        }
        $this->deleteAbsentFieldConfigs($fieldConfigIds, $accountId, $editorUserid);
    }

    protected function deleteAbsentFieldConfigs(array $existingFieldConfigIds, int $accountId, int $editorUserId): void
    {
        $fieldConfigs = $this->createAuctionFieldConfigLoader()->loadAll($accountId);
        foreach ($fieldConfigs as $fieldConfig) {
            if (!in_array($fieldConfig->Id, $existingFieldConfigIds, true)) {
                $this->createAuctionFieldConfigDeleter()->delete($fieldConfig, $editorUserId);
            }
        }
    }

    protected function makeFieldConfigEntity(string $index, int $accountId): AuctionFieldConfig
    {
        $fieldConfig = $this->createEntityFactory()->auctionFieldConfig();
        $fieldConfig->AccountId = $accountId;
        $fieldConfig->Index = $index;
        $fieldConfig->Active = true;
        return $fieldConfig;
    }
}
