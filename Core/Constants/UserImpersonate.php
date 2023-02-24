<?php

namespace Sam\Core\Constants;

/**
 * Class User
 * @package Sam\Core\Constants
 */
class UserImpersonate
{
    /**
     * Caching adapters
     */
    // Native php session
    public const CA_NATIVE = 'native';
    // Key-value file storage based on visitor session id
    public const CA_FILE = 'file';
    // Simple array storage with life-time of caller's scope (for unit test)
    public const CA_ARRAY = 'array';
    /**
     * Available cache adapters (don't use array-adapter in business logic)
     * @var string[]
     */
    public const CACHE_ADAPTERS = [self::CA_FILE, self::CA_NATIVE];
    /** @var string[] */
    public const CACHE_ADAPTER_NAMES = [
        self::CA_FILE => 'Key-value file storage',
        self::CA_NATIVE => 'Native php session'
    ];
}
