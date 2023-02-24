<?php
/**
 * My Alerts List Updater Create Trait
 *
 * SAM-6299: Refactor My Alerts List page at client side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 17, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\MyAlertsList\Save;

/**
 * Trait MyAlertsUpdaterCreateTrait
 */
trait MyAlertsUpdaterCreateTrait
{
    protected ?MyAlertsUpdater $myAlertsUpdater = null;

    /**
     * @return MyAlertsUpdater
     */
    protected function createMyAlertsUpdater(): MyAlertsUpdater
    {
        $myAlertsUpdater = $this->myAlertsUpdater ?: MyAlertsUpdater::new();
        return $myAlertsUpdater;
    }

    /**
     * @param MyAlertsUpdater $myAlertsUpdater
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setMyAlertsUpdater(MyAlertsUpdater $myAlertsUpdater): static
    {
        $this->myAlertsUpdater = $myAlertsUpdater;
        return $this;
    }
}
