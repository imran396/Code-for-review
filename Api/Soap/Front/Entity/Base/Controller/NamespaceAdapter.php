<?php
/**
 * Calculate primary key based on the namespace id.
 * Add missing Entity fields for DTO.
 *
 * SAM-3874 Refactor SOAP service and apply unit tests
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 21, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\Soap\Front\Entity\Base\Controller;

use Account;
use Auction;
use AuctionLotItem;
use InvalidArgumentException;
use Location;
use LotCategory;
use LotItem;
use User;

/**
 * Class NamespaceAdapter
 * @package Sam\EntityMaker\Base
 */
abstract class NamespaceAdapter
{
    protected ?int $accountId;
    /** @var object&\stdClass $data */
    protected object $data;
    protected Account|Auction|AuctionLotItem|Location|LotCategory|LotItem|User|null $entity = null;
    protected string $entityName;
    /**
     * @var string
     */
    protected string $namespace;
    protected ?int $namespaceId;
    protected string $samNamespaceId;

    abstract protected function loadEntityByNamespace(): Account|Auction|AuctionLotItem|Location|LotCategory|LotItem|User|null;

    /**
     * NamespaceAdapter constructor
     * @param object $data Lot data
     * @param string $namespace The namespace
     * @param int|null $namespaceId The namespace id
     * @param int|null $accountId
     */
    public function __construct(object $data, string $namespace, ?int $namespaceId, ?int $accountId = null)
    {
        $this->data = $data;
        $this->accountId = $accountId;
        $this->namespace = $namespace;
        $this->namespaceId = $namespaceId;

        $this->addId();
        $this->addSyncNamespaceId();
    }

    /**
     * @return Account|Auction|AuctionLotItem|Location|LotCategory|LotItem|User
     */
    public function getEntity(): Account|Auction|AuctionLotItem|Location|LotCategory|LotItem|User
    {
        return $this->entity;
    }

    /**
     * Return modified data
     * @return array
     */
    public function toArray(): array
    {
        return (array)$this->data;
    }

    /**
     * Return modified data
     * @return \stdClass
     */
    public function toObject(): object
    {
        return $this->data;
    }

    /**
     * Add id to $data
     */
    protected function addId(): void
    {
        if (isset($this->data->Key)) {
            $entity = $this->loadEntityByNamespace();
            if (!$entity) {
                throw new InvalidArgumentException("{$this->entityName} {$this->data->Key} not found within sync namespace " . $this->namespace);
            }

            $this->entity = $entity;
            $this->data->Id = $entity->Id;
            unset($this->data->Key);
        }
    }

    /**
     * Add SyncNamespaceId to $data
     */
    protected function addSyncNamespaceId(): void
    {
        if (
            isset($this->data->SyncKey)
            && $this->namespace === $this->samNamespaceId
        ) {
            throw new InvalidArgumentException('Please enter a custom namespace to create or update a SyncKey');
        }
        $this->data->SyncNamespaceId = $this->namespaceId;
    }
}
