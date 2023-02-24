<?php
/**
 * SAM-10962: Introduce CORS to GraphQL entry point
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 19, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

use Sam\Core\Constants;

/**
 * Class SystemParameterSystemSecurityPanelConstants
 * @package Sam\Core\Constants\Admin
 */
class SystemParameterSystemSecurityPanelConstants
{
    public const CID_TXT_GRAPH_QL_CORS_ALLOWED_ORIGINS = 'spssp_graphql_cors_allowed_origins';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_TXT_GRAPH_QL_CORS_ALLOWED_ORIGINS => Constants\Setting::GRAPHQL_CORS_ALLOWED_ORIGINS,
    ];
}
