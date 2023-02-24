<?php
/**
 * Dto for partial upload
 *
 * SAM-6575: Lot Csv Import - Extract session operations to separate adapter
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\PartialUpload\Internal\Model;

use Sam\Core\Service\CustomizableClass;

/**
 * Class OriginalUserIdentity
 */
class PartialUploadDto extends CustomizableClass
{
    /**
     * Pointer to csv row
     */
    public int $pointer = 0;
    /**
     * Total rows in csv file
     */
    public int $total = 0;
    public ?object $option = null;
    public ?object $progressData = null;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
