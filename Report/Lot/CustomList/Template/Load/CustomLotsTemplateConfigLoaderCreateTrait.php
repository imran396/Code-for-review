<?php
/**
 * SAM-4740: Avoid calling of load functions from data class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 07, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Lot\CustomList\Template\Load;

/**
 * Trait CustomLotsTemplateConfigLoaderCreateTrait
 * @package Sam\Report\Lot\CustomList\Template
 */
trait CustomLotsTemplateConfigLoaderCreateTrait
{
    protected ?CustomLotsTemplateConfigLoader $customLotsTemplateConfigLoader = null;

    /**
     * @return CustomLotsTemplateConfigLoader
     */
    protected function createCustomLotsTemplateConfigLoader(): CustomLotsTemplateConfigLoader
    {
        return $this->customLotsTemplateConfigLoader ?: CustomLotsTemplateConfigLoader::new();
    }

    /**
     * @param CustomLotsTemplateConfigLoader $customLotsTemplateConfigLoader
     * @return static
     * @internal
     */
    public function setCustomLotsTemplateConfigLoader(CustomLotsTemplateConfigLoader $customLotsTemplateConfigLoader): static
    {
        $this->customLotsTemplateConfigLoader = $customLotsTemplateConfigLoader;
        return $this;
    }
}
