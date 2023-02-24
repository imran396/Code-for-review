<?php

namespace Sam\Core\Service;

/**
 * Abstract class for implementing a customizable Singleton pattern
 *
 * NOTE: Implementing class needs to be an abstract class or implement getInstance method
 * which calls protected static method _getInstance with __CLASS__ as parameter
 * <code>
 * public static function new(): static {
 *     return parent::_getInstance(__CLASS__);
 * }
 * </code>
 */
abstract class Singleton implements SingletonInterface
{
    // store instances
    private static $_instance = [];

    /**
     * Hide the constructor from direct access
     * Use ::new() of the extending class instead
     */
    protected function __construct()
    {
    }

    /**
     * Singleton classes are not supposed to be cloned!
     */
    private function __clone()
    {
    }

    /**
     * To initialize instance properties
     * @return static
     */
    public function initInstance(): static
    {
        return $this;
    }

    /**
     * Gets an instance of this singleton. If no instance exists, a new instance is created and returned.
     * If one does exist, then the existing instance is returned.
     * Extending class needs to pass its class name
     * @param string $class class name of class to be instantiated
     * @return mixed instance of $class or $class . 'Custom' if it exists
     */
    protected static function _getInstance(string $class)
    {
        // check whether there is already an existing instance
        if (!isset(self::$_instance[$class])) {
            // check whether a custom version of the class is available (without auto-load)
            $instanceClass = class_exists($class . 'Custom', false) ? $class . 'Custom' : $class;
            // create instance and store
            self::$_instance[$class] = new $instanceClass();
            self::$_instance[$class]->initInstance();
        }
        return self::$_instance[$class];
    }

    /**
     * Delete an existing instance
     * Only accessible from an extending class
     * @param string $class name of the singleton to delete
     */
    protected static function _kill(string $class): void
    {
        if (!isset(self::$_instance[$class])) {
            return;
        }
        self::$_instance[$class] = null;
        unset(self::$_instance[$class]);
    }

}
