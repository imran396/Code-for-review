<?php
/**
 * SAM-10374: Handle location logo via SOAP
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 29, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Location\Image\Save;

use Location;
use Sam\Application\Url\Build\Config\Image\LocationImageUrlConfig;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Settings\SettingFileHelperAwareTrait;
use Sam\Storage\WriteRepository\Entity\Location\LocationWriteRepositoryAwareTrait;

/**
 * Class LocationImageProducer
 * @package Sam\Location\Image
 */
class LocationImageProducer extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use SettingFileHelperAwareTrait;
    use LocationWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function saveImageByLink(Location $location, int $accountId, int $editorUserId): void
    {
        if (
            !$location->Logo
            || !filter_var($location->Logo, FILTER_VALIDATE_URL)
        ) {
            return;
        }

        // Original image file name is the same as thumbnail static images that are public accessible
        $originalImageFileName = LocationImageUrlConfig::new()
            ->construct($location->Id, $location->AccountId)
            ->fileName();
        $this->getSettingFileHelper()->saveImage(
            $location->Logo,
            $originalImageFileName,
            $accountId,
            Constants\Logo::LOCATION_LOGO,
            $location->Id
        );

        $location->Logo = $originalImageFileName;
        $this->getLocationWriteRepository()->saveWithModifier($location, $editorUserId);
    }
}
