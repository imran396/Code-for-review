<?php
/**
 * SAM-8680: Fix text transformations with unicode characters
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 20, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants;

/**
 * Class Sanitize
 * @package Sam\Core\Constants
 */
class TextTransform
{
    public const CHARACTER_ENCODING_ERROR_MARKER = '#CHAR-ENC-ERR#';
}
