<?php
/**
 * Calculate primary key based on the namespace id.
 * Add missing Entity fields for DTO.
 *
 * SAM-4241 Auction Entity Maker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 30, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\Soap\Front\Entity\Auction\Controller;

use Auction;
use Sam\Api\Soap\Front\Entity\Base\Controller\NamespaceAdapter;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\SaleNo\Parse\SaleNoParserCreateTrait;

/**
 * Class AuctionNamespaceAdapter
 * @package Sam\EntityMaker\Auction
 */
class AuctionNamespaceAdapter extends NamespaceAdapter
{
    use AuctionLoaderAwareTrait;
    use SaleNoParserCreateTrait;

    protected string $entityName = Auction::class;
    protected string $samNamespaceId = 'SAM auction.id';
    protected string $samNamespaceSaleNo = 'SAM auction.sale_no';

    /**
     * AuctionNamespaceAdapter constructor
     * @param object $data Lot data
     * @param string $namespace The namespace
     * @param int|null $namespaceId The namespace ID
     * @param int|null $accountId
     */
    public function __construct(object $data, string $namespace, ?int $namespaceId, ?int $accountId)
    {
        parent::__construct($data, $namespace, $namespaceId, $accountId);
        $this->addAuctionType();
        $this->addSaleFullNo();
    }

    /**
     * Add id to $data
     */
    protected function addAuctionType(): void
    {
        if ($this->entity) {
            $this->data->auctionType = $this->entity->AuctionType;
        }
    }

    /**
     * Add saleFullNo to $data
     */
    protected function addSaleFullNo(): void
    {
        if (
            isset($this->data->SyncKey)
            && $this->namespace === $this->samNamespaceSaleNo
        ) {
            $this->data->SaleFullNo = $this->data->SyncKey;
            unset($this->data->SyncKey);
        }
    }

    /**
     * @return Auction|null
     */
    protected function loadEntityByNamespace(): ?Auction
    {
        switch ($this->namespace) {
            case $this->samNamespaceId:
                return $this->getAuctionLoader()->load((int)$this->data->Key);
            case $this->samNamespaceSaleNo:
                $saleNoParser = $this->createSaleNoParser()->construct();
                $saleNo = (string)$this->data->Key;
                if (!$saleNoParser->validate($saleNo)) {
                    return null;
                }
                $saleNoParsed = $saleNoParser->parse($saleNo);
                return $this->getAuctionLoader()->loadBySaleNoParsed($saleNoParsed, $this->accountId);
            default:
                return $this->getAuctionLoader()->loadBySyncKey($this->data->Key, (int)$this->namespaceId, $this->accountId);
        }
    }
}
