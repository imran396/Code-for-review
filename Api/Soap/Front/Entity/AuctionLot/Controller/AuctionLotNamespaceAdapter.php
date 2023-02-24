<?php
/**
 * Calculate primary key based on the namespace ID,
 * Add missing Entity fields for DTO.
 */

namespace Sam\Api\Soap\Front\Entity\AuctionLot\Controller;

use AuctionLotItem;
use Sam\Api\Soap\Front\Entity\Base\Controller\NamespaceAdapter;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;

/**
 * Class AuctionLotNamespaceAdapter
 * @package Sam\EntityMaker\AuctionLot
 */
class AuctionLotNamespaceAdapter extends NamespaceAdapter
{
    use AuctionLoaderAwareTrait;
    use AuctionLotLoaderAwareTrait;

    protected string $entityName = AuctionLotItem::class;
    protected string $samNamespaceId = 'SAM auction_lot_item.id';

    /**
     * AuctionLotNamespaceAdapter constructor
     * @param object $data AuctionLot data
     * @param string $namespace The namespace
     * @param int|null $namespaceId The namespace id
     * @param int|null $accountId
     */
    public function __construct(object $data, string $namespace, ?int $namespaceId, ?int $accountId)
    {
        parent::__construct($data, $namespace, $namespaceId, $accountId);
        $this->addAuctionId();
        $this->addLotItemId();
        $this->addAuctionType();
    }

    /**
     * Add AuctionId to $data
     */
    protected function addAuctionId(): void
    {
        if ($this->entity) {
            $this->data->AuctionId = $this->entity->AuctionId;
        }
    }

    /**
     * Add AuctionType to $data
     */
    private function addAuctionType(): void
    {
        if (isset($this->data->AuctionId)) {
            $auction = $this->getAuctionLoader()->load((int)$this->data->AuctionId);
            if ($auction) {
                $this->data->AuctionType = $auction->AuctionType;
            }
        }
    }

    /**
     * Add LotItemId to $data
     */
    protected function addLotItemId(): void
    {
        if ($this->entity) {
            $this->data->LotItemId = $this->entity->LotItemId;
        }
    }

    /**
     * @return AuctionLotItem|null
     */
    protected function loadEntityByNamespace(): ?AuctionLotItem
    {
        return match ($this->namespace) {
            $this->samNamespaceId => $this->getAuctionLotLoader()->loadById((int)$this->data->Key),
            default => $this->getAuctionLotLoader()->loadBySyncKey($this->data->Key, (int)$this->namespaceId),
        };
    }
}
