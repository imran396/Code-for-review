<?php
/**
 * SAM-6584: Decouple observers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 11, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer;

use Sam\Core\Service\CustomizableClassInterface;

/**
 * Interface EntityUpdateObserverHandlerInterface
 * @package Sam\Observer
 */
interface EntityUpdateObserverHandlerInterface extends CustomizableClassInterface
{
    /**
     * Run on entity update if the handler is applicable
     *
     * @param EntityObserverSubject $subject
     */
    public function onUpdate(EntityObserverSubject $subject): void;

    /**
     * Check if handler is applicable
     *
     * @param EntityObserverSubject $subject
     * @return bool
     */
    public function isApplicable(EntityObserverSubject $subject): bool;
}
