<?php
/**
 * SAM-10338: Redact sensitive information in Soap error.log
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 01, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Log\Render\Config;

/**
 * Class RenderMode
 * @package Sam\Log\Render\Config
 */
enum RenderMode: string
{
    case PLAIN = 'plain';
    case HEX = 'hex';
    case BASE64 = 'base64';
    case NONE = 'none';
}
