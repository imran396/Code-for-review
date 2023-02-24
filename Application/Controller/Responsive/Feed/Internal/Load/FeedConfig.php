<?php
/**
 * SAM-10192: Move alone end-points to controllers
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 13, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Feed\Internal\Load;

use Sam\Core\Service\CustomizableClass;

/**
 * Class FeedConfig
 * @package Sam\Application\Controller\Responsive\Feed\Internal\Load
 */
class FeedConfig extends CustomizableClass
{
    public readonly string $feedType;
    public readonly bool $includeInReports;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(string $feedType, bool $includeInReports): static
    {
        $this->feedType = $feedType;
        $this->includeInReports = $includeInReports;
        return $this;
    }
}
