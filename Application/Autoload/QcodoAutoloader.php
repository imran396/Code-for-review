<?php
/**
 * Qcodo classes autoloader logic
 *
 * SAM-1921: Autoloading optimization
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Feb 19, 2014
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Autoload;

use QApplication;
use Sam\Core\Service\CustomizableClass;

/**
 * Class Autoloader
 * @package Sam\Application
 */
class QcodoAutoloader extends CustomizableClass
{
    // Variables for statistics

    /**
     * Return instance of QcodoAutoloader
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Autoloader
     * @param string $className class name to auto-load
     * @return void
     */
    public function autoload(string $className): void
    {
        if ($this->isKnownClass($className)) {
            $this->requireKnownClass($className);
        }
    }

    /**
     * @param string $className
     * @return bool
     */
    protected function isKnownClass(string $className): bool
    {
        if (stripos($className, 'qqnode') === 0) { // JIC, anyway QQNode<Entity> is called from <Entity>Gen only
            return array_key_exists(strtolower(substr($className, 6)), QApplication::$ClassFile);
        }
        return array_key_exists(strtolower($className), QApplication::$ClassFile);
    }

    /**
     * @param string $className
     */
    protected function requireKnownClass(string $className): void
    {
        if (stripos($className, 'qqnode') === 0) { // JIC, anyway QQNode<Entity> is called from <Entity>Gen only
            $className = substr($className, 6);
        }
        $classFullPath = QApplication::$ClassFile[strtolower($className)];
        require $classFullPath;
    }
}
