<?php
/**
 * Help methods for auction custom field data loading
 *
 * SAM-4903: Custom field control components refactoring
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 18, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Base\Help;

/**
 * Trait BaseCustomFieldHelperAwareTrait
 * @package Sam\CustomField\User\Help
 */
trait BaseCustomFieldHelperAwareTrait
{
    /**
     * @var BaseCustomFieldHelper|null
     */
    protected ?BaseCustomFieldHelper $baseCustomFieldHelper = null;

    /**
     * @return BaseCustomFieldHelper
     */
    protected function getBaseCustomFieldHelper(): BaseCustomFieldHelper
    {
        if ($this->baseCustomFieldHelper === null) {
            $this->baseCustomFieldHelper = BaseCustomFieldHelper::new();
        }
        return $this->baseCustomFieldHelper;
    }

    /**
     * @param BaseCustomFieldHelper $baseCustomFieldHelper
     * @return static
     * @internal
     */
    public function setBaseCustomFieldHelper(BaseCustomFieldHelper $baseCustomFieldHelper): static
    {
        $this->baseCustomFieldHelper = $baseCustomFieldHelper;
        return $this;
    }
}
