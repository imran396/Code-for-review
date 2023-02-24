<?php
/**
 * SAM-10709: Implement the Bearer authorization method for GraphQL endpoint
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 26, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Exception;

use GraphQL;

/**
 * Class AuthorizationError
 * @package Sam\Api\GraphQL\Auth
 */
class AuthorizationError extends GraphQL\Error\Error
{
    public function __construct(string $message, int $code)
    {
        parent::__construct(message: $message, extensions: ['code' => $code]);
    }

    /**
     * @inheritdoc
     */
    public function getCategory()
    {
        return 'authorization';
    }
}
