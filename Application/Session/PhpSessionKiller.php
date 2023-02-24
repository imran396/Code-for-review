<?php
/**
 * Close another running session
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Mar 29, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Session;

use Sam\Core\Service\CustomizableClass;

/**
 * Class SessionHelper
 * @package Sam\Application\Session
 */
class PhpSessionKiller extends CustomizableClass
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Drop php native session
     * @param string $sessionId
     */
    public function kill(string $sessionId): void
    {
        // 1. commit session if it's started.
        if (session_id() !== '') {
            session_write_close();
        }

        // 2. store current session id
        session_start();
        $currentSessionId = session_id();
        session_write_close();

        // 3. hijack then destroy session specified.
        session_id($sessionId);
        session_start();
        session_destroy();
        log_debug('Destroyed Session' . composeSuffix([session_name() => $sessionId]));
        session_write_close();

        if ($currentSessionId !== $sessionId) {
            // 4. restore current session id. If don't restore it, your current session will refer to the session you just destroyed!
            session_id($currentSessionId);
            session_start();
            session_write_close();
        }
    }
}
