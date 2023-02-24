<?php
/**
 * Calculate primary key based on the namespace ID
 */

namespace Sam\Api\Soap\Front\Entity\Account\Controller;

use Account;
use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Api\Soap\Front\Entity\Base\Controller\NamespaceAdapter;

/**
 * Class AccountNamespaceAdapter
 * @package Sam\EntityMaker\Account
 */
class AccountNamespaceAdapter extends NamespaceAdapter
{
    use AccountLoaderAwareTrait;

    protected string $entityName = Account::class;
    protected string $samNamespaceId = 'SAM account.id';

    /**
     * @return Account|null
     */
    protected function loadEntityByNamespace(): ?Account
    {
        return match ($this->namespace) {
            $this->samNamespaceId => $this->getAccountLoader()->load((int)$this->data->Key),
            default => $this->getAccountLoader()->loadBySyncKey($this->data->Key, $this->namespaceId),
        };
    }
}
