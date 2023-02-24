<?php
/**
 * Generates captcha image in temporary file,
 * saves in session secret value,
 * outputs image with HTTP header,
 * deletes temporary file.
 *
 * SAM-10192: Move alone end-points to controllers
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 13, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Image\Captcha;

use Sam\Application\Controller\Responsive\Image\Captcha\Internal\Create\ImageFileGenerator;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class CaptchaImageProducer
 * @package Sam\Application\Controller\Responsive\Image\Captcha
 */
class CaptchaImageProducer extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function produce(): void
    {
        header('Content-Type: image/png');

        $generator = new ImageFileGenerator();
        $generator->generateCaptchaImage();

        $_SESSION[Constants\Captcha::SESSION_KEY] = $generator->getSecret();

        $imagePath = path()->temporary() . '/' . $generator->getImageId() . '.png';

        if (file_exists($imagePath) !== false) {
            $content = @file_get_contents($imagePath);
            @unlink($imagePath);
            echo $content;
        }
    }

}
