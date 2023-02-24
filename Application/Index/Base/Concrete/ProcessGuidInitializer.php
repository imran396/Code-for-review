<?php
/**
 * SAM-5171: Application layer
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           1/3/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Index\Base\Concrete;

use Sam\Core\Application\Ui\Ui;
use Sam\Core\Service\CustomizableClass;
use Sam\Application\Process\ApplicationProcessGuidManagerCreateTrait;

/**
 * Class ProcessGuidInitializer
 * @package
 */
class ProcessGuidInitializer extends CustomizableClass
{
    use ApplicationProcessGuidManagerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function initialize(Ui $ui): void
    {
        $processGuidManager = $this->createApplicationProcessGuidManager();
        $processGuidManager->generateProcessGuid();

        if ($ui->isWeb()) {
            $this->initializeForWeb();
        }
    }

    protected function initializeForWeb(): void
    {
        $processGuidManager = $this->createApplicationProcessGuidManager();
        $processGuid = $processGuidManager->getProcessGuid();
        $name = strtolower($processGuidManager->getProcessGuidName());
        $processGuidHeaderName = str_replace(' ', '-', ucwords(str_replace('_', ' ', $name)));
        $header = $processGuidHeaderName . ': ' . $processGuid;  // eg. 'Sam-Process-Guid: aE1AdgUNr7RtrAUF'
        header($header);
    }
}
