<?php
/**
 * SAM-5883: Develop the architecture for the admin side translation
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 20, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Translation\Cache\Exception;

use Exception;

/**
 * Class CacheException
 * @package Sam\Translation\Cache
 */
class CacheException extends Exception
{
    /**
     * @param string $language
     * @param string $domain
     * @return self
     */
    public static function cacheMissing(string $language, string $domain): self
    {
        return new self(sprintf('No cache for language "%s" and domain "%s"', $language, $domain));
    }
}
