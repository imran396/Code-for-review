<?php
/**
 * Helping methods for account fields rendering
 *
 * SAM-4121: Account fields renderer
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 28, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Account\Render;

use Sam\Account\Image\Path\AccountLogoPathResolverCreateTrait;
use Sam\Application\Url\Build\Config\Image\AccountImageUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;

/**
 * Class AccountRenderer
 * @package Sam\Account\Render
 */
class AccountRenderer extends CustomizableClass
{
    use AccountLogoPathResolverCreateTrait;
    use CurrentDateTrait;
    use UrlBuilderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return <img> tag with account image
     * @param int $accountId
     * @return string
     */
    public function makeImageTag(int $accountId): string
    {
        $url = '';
        if ($this->createAccountLogoPathResolver()->existThumbnail($accountId)) {
            $url = $this->makeImageUrl($accountId);
        }
        $output = $url
            ? sprintf('<img src="%s" class="account-img" />', $url)
            : '';
        return $output;
    }

    /**
     * @param int $accountId
     * @return string
     */
    public function makeImageUrl(int $accountId): string
    {
        $accountImageUrl = $this->getUrlBuilder()->build(
            AccountImageUrlConfig::new()->construct($accountId)
        );
        return $accountImageUrl;
    }
}
