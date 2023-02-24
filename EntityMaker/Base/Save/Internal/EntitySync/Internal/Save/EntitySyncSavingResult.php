<?php
/**
 * Result object of EntitySyncSaver for testing purposes
 *
 * SAM-5015: Unite sync tables data scheme
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 19, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Base\Save\Internal\EntitySync\Internal\Save;

use EntitySync;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class EntitySyncSavingResult
 * @package Sam\EntityMaker\Base\Save\EntitySync
 */
class EntitySyncSavingResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const INFO_SYNC_KEY_NOT_DEFINED = 1;
    public const INFO_SYNC_NAMESPACE_NOT_DEFINED = 2;

    public const INFO_MESSAGES = [
        self::INFO_SYNC_KEY_NOT_DEFINED => 'Sync key not defined',
        self::INFO_SYNC_NAMESPACE_NOT_DEFINED => 'Sync namespace not defined',
    ];

    /**
     * @var EntitySync|null
     */
    public ?EntitySync $entitySync = null;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(): static
    {
        $this->getResultStatusCollector()->construct([], [], [], self::INFO_MESSAGES);
        return $this;
    }

    public function addInfo(int $code): static
    {
        $this->getResultStatusCollector()->addInfo($code);
        return $this;
    }

    /**
     * @return int[]
     * @internal
     */
    public function infoCodes(): array
    {
        return $this->getResultStatusCollector()->getInfoCodes();
    }
}
