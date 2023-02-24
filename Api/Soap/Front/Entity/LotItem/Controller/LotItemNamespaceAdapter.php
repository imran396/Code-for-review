<?php
/**
 * Calculate primary key based on the namespace ID,
 * Add missing Entity fields for DTO.
 *
 * SAM-4015: Auction Lot and Lot Item Entity Makers
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 7, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\Soap\Front\Entity\LotItem\Controller;

use InvalidArgumentException;
use LotItem;
use Sam\Api\Soap\Front\Entity\Base\Controller\NamespaceAdapter;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\ItemNo\Parse\LotItemNoParserCreateTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class LotItemNamespaceAdapter
 * @package Sam\EntityMaker\LotItem
 */
class LotItemNamespaceAdapter extends NamespaceAdapter
{
    use ConfigRepositoryAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotItemNoParserCreateTrait;
    use UserLoaderAwareTrait;

    protected string $entityName = LotItem::class;
    protected string $samNamespaceId = 'SAM lot_item.id';
    protected string $samNamespaceItemId = 'SAM lot_item.item_num';

    /**
     * LotItemNamespaceAdapter constructor
     * @param object $data Lot data
     * @param string $namespace The namespace
     * @param int|null $namespaceId The namespace ID
     * @param int|null $accountId
     */
    public function __construct(object $data, string $namespace, ?int $namespaceId, ?int $accountId)
    {
        parent::__construct($data, $namespace, $namespaceId, $accountId);
        $this->addItemNumbers();
    }

    /**
     * Add itemNum, itemNumExt to data
     */
    private function addItemNumbers(): void
    {
        if ($this->cfg()->get('core->lot->itemNo->concatenated')) {
            // https://bidpath.atlassian.net/browse/SAM-3247
            // if someone provides both and they are not the same it should throw an error indicating that the request is ambiguous.
            if ($this->isRequestAmbiguous()) {
                throw new InvalidArgumentException('The request is ambiguous. ItemFullNum is not equal Id');
            }

            if (
                $this->namespace === $this->samNamespaceItemId
                && !isset($this->data->ItemFullNum)
            ) {
                if (isset($this->data->SyncKey)) {
                    $this->data->ItemFullNum = $this->data->SyncKey;
                    unset($this->data->SyncKey);
                }
            }
        }
    }

    /**
     * @return bool
     */
    protected function isRequestAmbiguous(): bool
    {
        $namespaces = [
            'SAM auction.sale_no',
            'SAM lot_item.item_num',
            'SAM user.customer_no',
        ];

        if (!in_array($this->namespace, $namespaces, true)) {
            return false;
        }

        return isset($this->data->SyncKey, $this->data->ItemFullNum)
            && (string)$this->data->SyncKey !== (string)$this->data->ItemFullNum;
    }

    /**
     * @return LotItem|null
     */
    protected function loadEntityByNamespace(): ?LotItem
    {
        switch ($this->namespace) {
            case $this->samNamespaceId:
                return $this->getLotItemLoader()->load((int)$this->data->Key);
            case $this->samNamespaceItemId:
                $itemNoParsed = $this->createLotItemNoParser()
                    ->construct()
                    ->parse((string)$this->data->Key);
                return $this->getLotItemLoader()->loadByItemNoParsed($itemNoParsed, $this->accountId);
            default:
                return $this->getLotItemLoader()->loadBySyncKey($this->data->Key, $this->namespaceId, $this->accountId);
        }
    }
}
