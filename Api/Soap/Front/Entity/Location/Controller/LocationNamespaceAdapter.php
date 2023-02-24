<?php
/**
 * Calculate primary key based on the namespace ID
 */

namespace Sam\Api\Soap\Front\Entity\Location\Controller;

use Location;
use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Api\Soap\Front\Entity\Base\Controller\NamespaceAdapter;
use Sam\Location\Load\LocationLoaderAwareTrait;

/**
 * Class LocationNamespaceAdapter
 * @package Sam\EntityMaker\Account
 */
class LocationNamespaceAdapter extends NamespaceAdapter
{
    use AccountLoaderAwareTrait;
    use LocationLoaderAwareTrait;

    protected string $entityName = Location::class;
    protected string $samNamespaceId = 'SAM location.id';

    /**
     * @return Location|null
     */
    protected function loadEntityByNamespace(): ?Location
    {
        return match ($this->namespace) {
            $this->samNamespaceId => $this->getLocationLoader()->load((int)$this->data->Key),
            default => $this->getLocationLoader()->loadBySyncKey($this->data->Key, $this->namespaceId),
        };
    }
}
