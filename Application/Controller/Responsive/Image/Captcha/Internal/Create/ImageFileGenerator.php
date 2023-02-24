<?php
/**
 * Generate captcha image and secret value.
 *
 * SAM-10192: Move alone end-points to controllers
 * SAM-4031 : Apply Zend\Captcha https://bidpath.atlassian.net/browse/SAM-4031
 *
 * @copyright  2018 Bidpath, Inc.
 * @author     Imran Rahman
 * @package    com.swb.sam2
 * @version    $Id$
 * @since      jan 7, 2017
 * @copyright  Copyright 2018 by Bidpath, Inc. All rights reserved.
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Application\Controller\Responsive\Image\Captcha\Internal\Create;

use Laminas\Captcha\Image;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class Captcha
 * @package Sam\Image\Captcha
 */
class ImageFileGenerator extends Image
{
    use ConfigRepositoryAwareTrait;

    protected string $imageId = '';
    protected string $secret = '';

    public function __construct($options = null)
    {
        $options = array_merge(
            [
                'wordLen' => $this->cfg()->get('core->captcha->wordLen'),
                'font' => path()->font() . '/georgia.TTF',
                'width' => $this->cfg()->get('core->captcha->width'),
                'height' => $this->cfg()->get('core->captcha->height'),
                'dotNoiseLevel' => $this->cfg()->get('core->captcha->dotNoiseLevel'),
                'lineNoiseLevel' => $this->cfg()->get('core->captcha->lineNoiseLevel'),
                'imgDir' => path()->temporary(),
            ],
            $options ?? []
        );
        parent::__construct($options);
    }

    /**
     * Render image and output directly to browser
     * keep image id in session
     * Set font size as half of the height defined for image
     * @return void
     */
    public function generateCaptchaImage(): void
    {
        $this->setFontSize((int)($this->getHeight() * 0.5));
        $id = $this->generateRandomId();
        $this->secret = $this->generateWord();
        $this->generateImage($id, $this->secret);
        $this->imageId = $id;
    }

    /**
     * @return string
     */
    public function getImageId(): string
    {
        return $this->imageId;
    }

    /**
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }
}
