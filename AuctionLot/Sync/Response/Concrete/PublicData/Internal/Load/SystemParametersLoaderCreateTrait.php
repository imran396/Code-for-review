<?php
/**
 * SAM-6573: Refactor lot list data sync providers - structurize responses
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 25, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Sync\Response\Concrete\PublicData\Internal\Load;

/**
 * Trait SystemParametersLoaderCreateTrait
 * @package Sam\AuctionLot\Sync\Response\Concrete\PublicData\Internal\Load
 * @internal
 */
trait SystemParametersLoaderCreateTrait
{
    protected ?SystemParametersLoader $systemParametersLoader = null;

    /**
     * @return SystemParametersLoader
     */
    protected function createSystemParametersLoader(): SystemParametersLoader
    {
        return $this->systemParametersLoader ?: SystemParametersLoader::new();
    }

    /**
     * @param SystemParametersLoader $loader
     * @return static
     * @internal
     */
    public function setSystemParametersLoader(SystemParametersLoader $loader): static
    {
        $this->systemParametersLoader = $loader;
        return $this;
    }
}
