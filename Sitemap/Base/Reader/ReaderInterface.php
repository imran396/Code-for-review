<?php
/**
 * Interface for sitemap data reader
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           18 Dec, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sitemap\Base\Reader;

/**
 * Interface ReaderInterface
 * @package Sam\Sitemap\Base\Reader
 */
interface ReaderInterface
{
    /**
     * Return fetched portion of data or empty array, if there is no more data
     * @return array
     */
    public function read(): array;
}
