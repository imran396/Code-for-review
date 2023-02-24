<?php
/**
 * Close current running session
 *
 * SAM-5918: Improve parallel processing of concurrent requests of the same user session
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Jul 07, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Session;

use Sam\Core\Service\CustomizableClass;
use Sam\Infrastructure\OutputBuffer\OutputBufferCreateTrait;

/**
 * Class SessionHelper
 * @package Sam\Application\Session
 */
class PhpSessionCloser extends CustomizableClass
{
    use OutputBufferCreateTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Close currently running php native session to avoid session lock
     */
    public function close(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            /**
             * From comments of https://www.php.net/manual/en/function.session-write-close.php
             * If You apply session_write_close() to allow concurrent requests from a client
             * this may not resolve the problem, if You have enabled output buffering (default in PHP 7+).
             */
            $this->createOutputBuffer()->completeEndFlush();
            $isClosed = session_write_close();
            if (!$isClosed) {
                log_debug('Unable to close native php session by session_write_close()');
            }
        }
    }
}
