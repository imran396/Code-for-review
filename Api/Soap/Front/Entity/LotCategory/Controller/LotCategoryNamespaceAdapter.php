<?php
/**
 * Calculate primary key based on the namespace ID,
 * Add missing Entity fields for DTO.
 *
 * SAM-4048: LotCategory entity maker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 5, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\Soap\Front\Entity\LotCategory\Controller;

use LotCategory;
use Sam\Api\Soap\Front\Entity\Base\Controller\NamespaceAdapter;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;

/**
 * Class LotCategoryNamespaceAdapter
 * @package Sam\EntityMaker\LotCategory
 */
class LotCategoryNamespaceAdapter extends NamespaceAdapter
{
    use LotCategoryLoaderAwareTrait;

    protected string $entityName = LotCategory::class;
    protected string $samNamespaceId = 'SAM lot_category.id';
    protected string $samNamespaceItemId = 'SAM lot_category.name';

    /**
     * LotCategoryNamespaceAdapter constructor
     * @param object $data Lot data
     * @param string $namespace The namespace
     * @param int|null $namespaceId The namespace id
     */
    public function __construct(object $data, string $namespace, ?int $namespaceId)
    {
        parent::__construct($data, $namespace, $namespaceId);
        unset($this->data->SyncNamespaceId);
    }

    /**
     * @return LotCategory|null
     */
    protected function loadEntityByNamespace(): ?LotCategory
    {
        return match ($this->namespace) {
            $this->samNamespaceId => $this->getLotCategoryLoader()->load((int)$this->data->Key),
            default => $this->getLotCategoryLoader()->loadByName((string)$this->data->Key),
        };
    }
}
