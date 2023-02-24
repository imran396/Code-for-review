<?php
/**
 * SAM-11110: Stacked Tax. New Invoice Edit page: Service Fee Edit page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 23, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\ServiceFeeEditForm\Edit\Save;

/**
 * Trait ServiceFeeEditFormUpdaterCreateTrait
 * @package Sam\View\Admin\Form\ServiceFeeEditForm\Edit\Save
 */
trait ServiceFeeEditFormUpdaterCreateTrait
{
    protected ?ServiceFeeEditFormUpdater $serviceFeeEditFormUpdater = null;

    /**
     * @return ServiceFeeEditFormUpdater
     */
    protected function createServiceFeeEditFormUpdater(): ServiceFeeEditFormUpdater
    {
        return $this->serviceFeeEditFormUpdater ?: ServiceFeeEditFormUpdater::new();
    }

    /**
     * @param ServiceFeeEditFormUpdater $serviceFeeEditFormUpdater
     * @return static
     * @internal
     */
    public function setServiceFeeEditFormUpdater(ServiceFeeEditFormUpdater $serviceFeeEditFormUpdater): static
    {
        $this->serviceFeeEditFormUpdater = $serviceFeeEditFormUpdater;
        return $this;
    }
}
