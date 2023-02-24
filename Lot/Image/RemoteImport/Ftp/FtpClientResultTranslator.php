<?php
/**
 * SAM-10383: Refactor remote image import for v3-7
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 02, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\RemoteImport\Ftp;

use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Image\RemoteImport\Ftp\FtpClientResult as Result;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Class FtpClientResultTranslator
 * @package Sam\Lot\Image\RemoteImport
 */
class FtpClientResultTranslator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;

    protected const DOMAIN = 'admin_auction_lot_remote_image_import';

    protected const TRANSLATIONS = [
        Result::ERR_CANNOT_CONNECT => 'ftp.cannot_connect',
        Result::ERR_CANNOT_LOGIN => 'ftp.cannot_login',
        Result::ERR_CANNOT_CHANGE_DIRECTORY => 'ftp.cannot_change_directory',
        Result::ERR_CANNOT_TURN_ON_PASSIVE_MODE => 'ftp.cannot_turn_on_passive_mode',
        Result::ERR_CANNOT_READ_DIRECTORY => 'ftp.cannot_read_directory',
        Result::ERR_CANNOT_DOWNLOAD_FILE => 'ftp.cannot_download_file',
    ];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function translate(string $language, Result $result): string
    {
        $errorCode = $result->errorCode();
        $id = self::TRANSLATIONS[$errorCode] ?? null;
        if (!$id) {
            log_error("Translation id not found by error code" . composeSuffix(['code' => $errorCode]));
            return '';
        }

        $params = [];
        if ($errorCode === Result::ERR_CANNOT_DOWNLOAD_FILE) {
            $params['file'] = $result->remoteFile;
        }

        return $this->getAdminTranslator()->trans($id, $params, self::DOMAIN, $language);
    }
}
