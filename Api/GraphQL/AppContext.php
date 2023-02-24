<?php
/**
 * SAM-10319: Implement a GraphQL prototype for a list of auctions
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL;

use Sam\Core\Service\CustomizableClass;
use Sam\Api\GraphQL\Load\DeferredDataLoader;

/**
 * Class AppContext
 * @package Sam\Api\GraphQL
 */
class AppContext extends CustomizableClass
{
    public readonly int $systemAccountId;
    public readonly ?int $editorUserId;
    public readonly DeferredDataLoader $dataLoader;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(int $systemAccountId, ?int $editorUserId, DeferredDataLoader $dataLoader): static
    {
        $this->systemAccountId = $systemAccountId;
        $this->editorUserId = $editorUserId;
        $this->dataLoader = $dataLoader;
        return $this;
    }
}
