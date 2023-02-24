<?php

namespace Sam\Core\Service;

require_once __DIR__ . '/CustomizableClassInterface.php';

/**
 * Abstract class for implementing a customizable class pattern
 *
 * Extending class needs to implement getInstance method
 * and call protected static method _getInstance with __CLASS__ as parameter
 * <code>
 * public static function new(): static {
 *     return parent::_getInstance(__CLASS__);
 * }
 * </code>
 */
abstract class CustomizableClass implements CustomizableClassInterface
{
    /**
     * To initialize instance properties
     * @return static
     */
    public function initInstance()
    {
        return $this;
    }

    /**
     * Returns an instance of the class
     * @param string $class class name
     * @return mixed instance of $class or $class .'Custom' if available
     */
    protected static function _new(string $class)
    {
        $instanceClass = class_exists($class . 'Custom', false) ? $class . 'Custom' : $class;
        /** @var self $instance */
        $instance = new $instanceClass();
        $instance->initInstance();
        return $instance;
    }

}
