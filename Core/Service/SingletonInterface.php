<?php

namespace Sam\Core\Service;

/**
 * Interface Singleton_Interface
 */
interface SingletonInterface
{
    /**
     * Implementing class needs to be an abstract class or implement getInstance method
     * which calls protected static method _getInstance with __CLASS__ as parameter
     * <code>
     * public static function new(): static {
     *     return parent::_getInstance(__CLASS__);
     * }
     * </code>
     */
    public static function getInstance();
}
