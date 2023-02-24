<?php
/**
 * SAM-9610: Response with "Bad Request" when detected unexpected request parameter value
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 28, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Index\Base\Exception;

class UnexpectedValueOfRequestParameter extends BadRequest
{
    public static function withClarification(string $name, $value, string $constraint): self
    {
        $message = "Unexpected value of request parameter"
            . composeSuffix(['parameter' => $name, 'value' => $value, 'constraint' => $constraint]);
        log_debugBackTrace($message);
        return new self($message);
    }
}
