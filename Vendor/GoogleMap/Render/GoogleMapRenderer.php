<?php
/**
 * SAM-4238: Google Map and Youtube render helper classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/5/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Vendor\GoogleMap\Render;

use Sam\Core\Service\CustomizableClass;

/**
 * TODO IM: This class is never used
 *
 * Class GoogleMapRenderer
 * @package Sam\Vendor\GoogleMap\Render
 */
class GoogleMapRenderer extends CustomizableClass
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
     * Return html for google map for an address
     * @param string $url
     * @param int $width optional map width
     * @param int $height optional map height
     * @param int $zoom optional zoom factor
     * @return string google map html
     */
    public function render(string $url, int $width = 425, int $height = 350, int $zoom = 15): string
    {
        $url = urlencode($url);
        $output = <<<GMAP
<div style="width:{$width}px;height:{$height}px"><iframe width="{$width}" height="{$height}" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="//maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q={$url}&ie=UTF8&z={$zoom}&t=m&iwloc=near&output=embed" sandbox></iframe></div>
GMAP;
        return $output;
    }
}
