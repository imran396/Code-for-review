<?php
/**
 * Calculate primary key based on the namespace id.
 * Add missing Entity fields for DTO.
 *
 * SAM-8841: User entity-maker module structural adjustments for v3-5
 * SAM-4989: User Entity Maker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 22, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\Soap\Front\Entity\User\Controller;

use Sam\Api\Soap\Front\Entity\Base\Controller\NamespaceAdapter;
use Sam\User\Load\UserLoaderAwareTrait;
use User;

/**
 * Class UserNamespaceAdapter
 * @package Sam\EntityMaker\User
 */
class UserNamespaceAdapter extends NamespaceAdapter
{
    use UserLoaderAwareTrait;

    protected string $entityName = User::class;
    protected string $samNamespaceId = 'SAM user.id';
    protected string $samNamespaceCustomerNo = 'SAM user.customer_no';

    /**
     * @return null|User
     */
    protected function loadEntityByNamespace(): ?User
    {
        return match ($this->namespace) {
            $this->samNamespaceId => $this->getUserLoader()->load((int)$this->data->Key),
            $this->samNamespaceCustomerNo => $this->getUserLoader()->loadByCustomerNo((int)$this->data->Key),
            default => $this->getUserLoader()->loadBySyncKey($this->data->Key, $this->namespaceId, $this->accountId),
        };
    }
}
