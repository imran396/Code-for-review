<?php
/**
 * SAM-10319: Implement a GraphQL prototype for a list of auctions
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 29, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type;


use RuntimeException;
use Sam\Api\GraphQL\AppContext;

/**
 * Default realisation for AppContextAwareInterface
 *
 * Trait AppContextAwareTrait
 * @package Sam\Api\GraphQL\Type
 */
trait AppContextAwareTrait
{
    protected ?AppContext $appContext = null;

    /**
     * @return AppContext
     */
    protected function getAppContext(): AppContext
    {
        if (!$this->appContext) {
            throw new RuntimeException('AppContext is not set');
        }
        return $this->appContext;
    }

    /**
     * @param AppContext $appContext
     * @return static
     * @internal
     */
    public function setAppContext(AppContext $appContext): void
    {
        $this->appContext = $appContext;
    }
}
