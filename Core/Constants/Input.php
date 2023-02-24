<?php

namespace Sam\Core\Constants;

/**
 * Class Report
 * @package Sam\Core\Constants
 */
class Input
{
    public const WEB = 'web';
    public const CSV = 'csv';
    public const SOAP = 'soap';

    /** @var string[] */
    public static array $modes = [self::WEB, self::CSV, self::SOAP];
}
