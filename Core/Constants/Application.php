<?php
/**
 * SAM-9546: Application layer adjustment for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants;

class Application
{
    public const UI_RESPONSIVE = 1;
    public const UI_ADMIN = 2;
    public const UI_CLI = 3;

    /** @var int[] */
    public static array $uis = [self::UI_RESPONSIVE, self::UI_ADMIN, self::UI_CLI];

    public const UIDIR_RESPONSIVE = 'm';
    public const UIDIR_ADMIN = 'admin';
    public const UIDIR_CLI = 'bin';

    /** @var string[] */
    public static array $uiDirs = [
        self::UI_RESPONSIVE => self::UIDIR_RESPONSIVE,
        self::UI_ADMIN => self::UIDIR_ADMIN,
        self::UI_CLI => self::UIDIR_CLI
    ];
}
