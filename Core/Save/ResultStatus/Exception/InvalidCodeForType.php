<?php
/**
 * SAM-5845: Adjust ResultStatusCollector
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 20, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Save\ResultStatus\Exception;

use LogicException;
use Sam\Core\Save\ResultStatus\ResultStatus;

class InvalidCodeForType extends LogicException
{
    public static function withDefaultMessage(int $type, ?int $code): self
    {
        $typeName = ResultStatus::new()->nameByType($type);
        $codeString = (string)($code ?? "NULL");
        $message = "Invalid code value for type of the result status"
            . composeSuffix(['code' => $codeString, 'type name' => $typeName, 'type' => $type]);
        log_errorBackTrace($message);
        return new self($message);
    }
}
