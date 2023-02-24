<?php
/**
 * Consignor privileges checking service.
 *
 * Related tickets:
 * SAM-3042 : Several issues in Item consigned tab. https://bidpath.atlassian.net/browse/SAM-3042
 *
 * @author        Imran Rahman
 * Filename       ConsignorPrivilegeChecker.php
 * @version       SAM 2.0
 * @since         July 22, 2017
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 * @package       com.swb.sam2.api
 */

namespace Sam\User\Privilege\Validate;

use Consignor;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class ConsignorPrivilegeChecker
 * @package Sam\User\Privilege\Validate
 */
class ConsignorPrivilegeChecker extends CustomizableClass
{
    use UserLoaderAwareTrait;

    protected ?Consignor $consignor = null;
    protected bool $isReadOnlyDb = false;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Use read-only db, if it is available
     * @param bool $enable
     * @return static
     */
    public function enableReadOnlyDb(bool $enable): static
    {
        $this->isReadOnlyDb = $enable;
        return $this;
    }

    /**
     * @param int|null $userId
     * @return static
     */
    public function initByUserId(?int $userId = null): static
    {
        $consignor = $this->getUserLoader()->loadConsignor($userId, $this->isReadOnlyDb);
        $this->initByConsignor($consignor);
        return $this;
    }

    /**
     * @param Consignor|null $consignor
     * @return static
     */
    public function initByConsignor(?Consignor $consignor): static
    {
        $this->consignor = $consignor;
        return $this;
    }

    /**
     * @return Consignor|null
     */
    public function getConsignor(): ?Consignor
    {
        return $this->consignor;
    }

    /**
     * Has consignor role
     * @return bool
     */
    public function isConsignor(): bool
    {
        $has = $this->getConsignor() instanceof Consignor
            && $this->getConsignor()->Id > 0;
        return $has;
    }
}
