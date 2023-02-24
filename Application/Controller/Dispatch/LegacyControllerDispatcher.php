<?php
/**
 * SAM-5844: Move controller customization logic to \Sam\Application\Controller\Dispatch namespace
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 06, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Dispatch;

use Sam\Application\Index\Base\Concrete\LegacyFrontController;
use Sam\File\FilePathHelperAwareTrait;
use Zend_Controller_Dispatcher_Standard;

/**
 * Extension of Standard dispatcher allows to define custom controllers
 * Controller class file name has to be in the same location in a sub-folder of custom
 * for example custom/app/m/AuctionsController.php
 * "Custom" must be appended to class name and class needs to
 * extend original controller class
 * For example
 * class AuctionsControllerCustom extends AuctionsController
 *
 * Class LegacyControllerDispatcher
 * @author tom
 * @package Sam\Application\Controller
 */
class LegacyControllerDispatcher extends Zend_Controller_Dispatcher_Standard
{
    use FilePathHelperAwareTrait;

    /**
     * Extend function to load from custom/app/... folder if
     * custom class is available
     * @param string $className
     * @return string
     * @throws \Zend_Controller_Dispatcher_Exception
     *
     * TODO: refactor when we will change our MVC layer
     * @see Zend_Controller_Dispatcher_Standard::loadClass()
     */
    public function loadClass($className): string
    {
        $customClassName = $className . 'Custom';
        $classDirectory = $this->getControllerDirectory(LegacyFrontController::ZF1_MODULE_DEFAULT);
        $classFileName = $this->classToFilename($className);
        $classPath = $classDirectory . DIRECTORY_SEPARATOR . $classFileName;
        $customClassDirectory = $this->getControllerDirectory(LegacyFrontController::ZF1_MODULE_CUSTOM);
        $customClassPath = $customClassDirectory . DIRECTORY_SEPARATOR . $classFileName;
        if ($this->getFilePathHelper()->isReadable($customClassPath)) {
            if ($this->getFilePathHelper()->isReadable($classPath)) {
                include_once $classPath;
            }
            include_once $customClassPath;
            log_debug(composeSuffix(['Loaded customized class' => $customClassName]));
            return $customClassName;
        }
        return parent::loadClass($className);
    }
}
