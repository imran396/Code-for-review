<?php
/**
 * SAM-6455: Move classes from global namespace to \Sam
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 01, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Service;

interface CustomizableClassInterface
{
    /**
     * Extending class needs to be abstract class or implement getInstance method
     * and call protected static method _getInstance with __CLASS__ as parameter
     * <code>
     * public static function new(): static {
     *     return parent::_getInstance(__CLASS__);
     * }
     * </code>
     */
    public static function new(): static;

    /**
     * Initialize state of instance, is called by getInstance().
     * It is public method, can be called again, but we need to adjust current code so it could return $this
     * @return static
     */
    public function initInstance();
}
