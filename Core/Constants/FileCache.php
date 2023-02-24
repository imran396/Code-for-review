<?php

namespace Sam\Core\Constants;

/**
 * Class FileCache
 * @package Sam\Core\Constants
 */
class FileCache
{
    public const SETTINGS_OBJECT_ACCOUNT_ID = 'SettingsObject-Class-%s-AccountId-%d';
    public const TIMEZONE_ACCOUNT_ID = 'Timezone-AccountId-%d';

    /** @var int[] */
    public static array $gzipCompressionLevels = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];

    public const FT_MD5 = 'md5';
    public const FT_PLAIN = 'plain';
    /** @var string[] */
    public static array $filenameTransformations = [self::FT_MD5, self::FT_PLAIN];
}
