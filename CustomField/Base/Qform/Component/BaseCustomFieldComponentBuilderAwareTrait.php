<?php
/**
 * SAM-4903: Custom field control components refactoring
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @since           Oct 17, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Base\Qform\Component;

/**
 * Trait BaseCustomFieldComponentBuilderAwareTrait
 * @package Sam\CustomField\Base\Qform\Component
 */
trait BaseCustomFieldComponentBuilderAwareTrait
{
    /**
     * @var BaseCustomFieldComponentBuilder|null
     */
    protected ?BaseCustomFieldComponentBuilder $baseCustomFieldComponentBuilder = null;

    /**
     * @return BaseCustomFieldComponentBuilder
     */
    protected function getBaseCustomFieldComponentBuilder(): BaseCustomFieldComponentBuilder
    {
        if ($this->baseCustomFieldComponentBuilder === null) {
            $this->baseCustomFieldComponentBuilder = BaseCustomFieldComponentBuilder::new();
        }
        return $this->baseCustomFieldComponentBuilder;
    }

    /**
     * @param BaseCustomFieldComponentBuilder $baseCustomFieldComponentBuilder
     * @return static
     * @internal
     */
    public function setBaseCustomFieldComponentBuilder(BaseCustomFieldComponentBuilder $baseCustomFieldComponentBuilder): static
    {
        $this->baseCustomFieldComponentBuilder = $baseCustomFieldComponentBuilder;
        return $this;
    }
}
