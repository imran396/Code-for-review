<?php
/**
 * Template sample renderer
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         May 9, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Auction\Feed;

/**
 * Class TemplateSampler
 * @package Sam\Details
 */
class TemplateSampler
    extends \Sam\Details\Auction\Base\TemplateSampler
{
    use ConfigManagerAwareTrait;

    public static function new(): static
    {
        return self::_new(self::class);
    }
}
