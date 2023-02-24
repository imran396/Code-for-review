<?php
/**
 * Generate and output barcode image.
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

namespace Sam\Application\Controller\Responsive\Image\Barcode;

use Exception;
use GdImage;
use Image_Barcode2;
use Sam\Application\Redirect\ApplicationRedirectorCreateTrait;
use Sam\Application\Url\Build\Config\Barcode\BarcodeUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Base\TextType\Barcode\Validate\BarcodeValidator;
use Sam\Infrastructure\OutputBuffer\OutputBufferCreateTrait;

/**
 * Class BarcodeOutputProducer
 * @package Sam\Application\Controller\Responsive\Image\Barcode
 */
class BarcodeOutputProducer extends CustomizableClass
{
    use ApplicationRedirectorCreateTrait;
    use OutputBufferCreateTrait;
    use UrlBuilderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Generate and output barcode image.
     * @param string $code Barcode value.
     * @param int|null $type Barcode type.
     * @param string $render When 'html', then output in <img> tag, otherwise as binary image content.
     */
    public function produce(
        string $code,
        ?int $type,
        string $render
    ): void {
        if (!$code) {
            $this->createApplicationRedirector()->badRequest();
        }
        if (!$type) {
            $this->createApplicationRedirector()->badRequest();
        }

        $this->createOutputBuffer()->clean();

        $code = strtoupper($code);

        if (!array_key_exists($type, Constants\CustomField::$barcodeTypeNames)) {
            log_error('Invalid Barcode Type');
            $this->createApplicationRedirector()->badRequest();
        }

        $barcodeValidator = BarcodeValidator::new()
            ->setBarcode($code)
            ->setType($type);

        if (!$barcodeValidator->validate()) {
            $errorMessage = $barcodeValidator->getErrorMessage();
            echo $errorMessage;
            return;
        }

        /**
         * This is for window.print() to also work in IE
         */
        if ($render === 'html') {
            $url = $this->getUrlBuilder()->build(BarcodeUrlConfig::new()->forWeb($code, $type));
            echo sprintf('<img src="%s" alt=""/><script>window.print();</script>', $url);
            return;
        }

        set_include_path(get_include_path() . PATH_SEPARATOR . path()->libs() . '/PEAR');
        require_once path()->libs() . '/PEAR/Image/Barcode2.php';

        $img = null;
        try {
            /** @var GdImage $img */
            $img = Image_Barcode2::draw($code, Constants\CustomField::$barcodeTypeNames[$type], 'jpg', false);
        } catch (Exception $e) {
            log_error("PHP exception is thrown" . composeSuffix(['message' => $e->getMessage(), 'code' => $e->getCode()]));
        }

        if (!$img instanceof GdImage) {
            $this->createApplicationRedirector()->badRequest();
        }

        header('Content-type: image/jpg');
        imagejpeg($img);
        imagedestroy($img);
    }

}
