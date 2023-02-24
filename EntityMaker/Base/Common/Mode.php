<?php
/**
 * SAM-8837: Lot item entity maker module structural adjustments for v3-5
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 29, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Base\Common;

/**
 * Class Mode
 * @package Sam\EntityMaker\Base\Common
 */
enum Mode
{
    case CSV;
    case SOAP;
    case WEB_ADMIN;
    case WEB_RESPONSIVE;
    case SSO_RESPONSIVE;

    /**
     * @return bool
     */
    public function isCsv(): bool
    {
        return $this === self::CSV;
    }

    /**
     * @return bool
     */
    public function isSoap(): bool
    {
        return $this === self::SOAP;
    }

    /**
     * @return bool
     */
    public function isWebAdmin(): bool
    {
        return $this === self::WEB_ADMIN;
    }

    /**
     * @return bool
     */
    public function isWebResponsive(): bool
    {
        return $this === self::WEB_RESPONSIVE;
    }

    /**
     * @return bool
     */
    public function isSsoResponsive(): bool
    {
        return $this === self::SSO_RESPONSIVE;
    }
}
