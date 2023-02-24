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

namespace Sam\Vendor\Youtube\Render;

use Sam\Core\Service\CustomizableClass;

/**
 * Class YoutubeRenderer
 * @package Sam\Vendor\Youtube\Render
 */
class YoutubeRenderer extends CustomizableClass
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
     * Returns the video player html for a video URL
     * (currently only youtube URLs)
     * @param string $url
     * @param int $width optional player width
     * @param int $height optional player height
     * @return string player html
     */
    public function render(string $url, int $width = 300, int $height = 168): string
    {
        $output = '';
        $youtubeId = $this->parseYoutubeUrl($url);
        if ($youtubeId) {
            $output = <<<YOUTUBE
<iframe width="{$width}" height="{$height}" src="//www.youtube.com/embed/{$youtubeId}?rel=0&wmode=transparent" frameborder="0" allowfullscreen sandbox="allow-scripts allow-same-origin"></iframe>
YOUTUBE;
        }
        return $output;
    }

    /**
     * Parse a string and return Youtube video ID
     * @param string $url
     * @return string Youtube video ID or false
     */
    protected function parseYoutubeUrl(string $url): string
    {
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
            return $match[1];
        }
        return '';
    }
}
