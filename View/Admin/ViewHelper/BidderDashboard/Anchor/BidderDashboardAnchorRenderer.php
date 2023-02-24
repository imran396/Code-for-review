<?php
/**
 * Render html element that where we bind tooltip display on mouseover event.
 *
 * SAM-10226: Refactor bidder dashboard tooltip for v3-7
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 19, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\ViewHelper\BidderDashboard\Anchor;

use Sam\Core\Constants\Admin\BidderDashboardTooltipConstants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class BidderDashboardAnchorRenderer
 * @package Sam\View\Admin\ViewHelper\BidderDashboard\Anchor
 */
class BidderDashboardAnchorRenderer extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * We bind "Bidder Dashboard" on mouseover to username by "tooltip" css class.
     * See, \assets\js\src\Admin\ManageAuctions\Bidders\BidderDashboard.ts
     * @param int $userId
     * @param string $content
     * @return string
     */
    public function render(int $userId, string $content): string
    {
        $cidBlkUserDashboard = sprintf(BidderDashboardTooltipConstants::CID_BLK_USER_DASHBOARD_TPL, $userId);
        $cssArrowBox = BidderDashboardTooltipConstants::CSS_ARROW_BOX;
        $cssTooltipContent = BidderDashboardTooltipConstants::CSS_TOOLTIP_CONTENT;
        $cssHiddenUserId = BidderDashboardTooltipConstants::CSS_HIDDEN_USER_ID;
        $cssTooltip = BidderDashboardTooltipConstants::CSS_TOOLTIP;
        $output = <<<HTML
<span style="cursor: default !important; position: relative !important;" class="sam-help {$cssTooltip} user-bidder-info" tabindex="-1">
    {$content}
    <div class="{$cssArrowBox}"></div>
    <span class="{$cssTooltipContent} sam-bidder-dashboard" id="{$cidBlkUserDashboard}"></span>
    <input type="hidden" value="{$userId}" class="{$cssHiddenUserId}" />
</span>
HTML;
        return $output;
    }

}
