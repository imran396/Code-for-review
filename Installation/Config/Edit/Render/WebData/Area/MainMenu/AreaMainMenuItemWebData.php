<?php
/**
 * Navigation among config areas (first node of config option)
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           2/4/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Render\WebData\Area\MainMenu;

/**
 * Class AreaNavigation
 * @package
 */
final class AreaMainMenuItemWebData
{
    /** @var string */
    private string $name;
    /** @var string */
    private string $urlHash;

    /**
     * AreaNavigation constructor.
     * @param string $name
     * @param string $urlHash
     */
    public function __construct(string $name, string $urlHash)
    {
        $this->name = $name;
        $this->urlHash = $urlHash;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUrlHash(): string
    {
        return $this->urlHash;
    }
}
