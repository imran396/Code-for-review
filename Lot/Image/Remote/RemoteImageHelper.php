<?php
/**
 * Helper method for remote images
 *
 * SAM-4464: Apply Lot Image modules
 *
 * @copyright         2018 Bidpath, Inc.
 * @author            Oleg Kovalov
 * @package           com.swb.sam2
 * @version           SVN: $Id$
 * @since             26 Dec, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 * @package           com.swb.sam2.api
 *
 */

namespace Sam\Lot\Image\Remote;

use Sam\Core\Service\CustomizableClass;

/**
 * Class RemoteImageHelper
 * @package Sam\Lot\Image\Remote
 */
class RemoteImageHelper extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $url
     * @return float
     */
    public function detectRemoteImageSize(string $url): float
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $floatSize = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
        return $floatSize;
    }
}
