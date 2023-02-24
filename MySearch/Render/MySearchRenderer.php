<?php
/**
 * SAM-6473: Move "my_search" table related logic to separate module
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 15, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\MySearch\Render;


use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\MySearch\Load\MySearchAuctionLotLoaderCreateTrait;
use Sam\MySearch\Load\MySearchLoaderCreateTrait;
use Sam\MySearch\MySearchLinkBuilderCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\User\Alert\SentLot\UserAlertSentLotManagerCreateTrait;

/**
 * Class MySearchRenderer
 * @package Sam\MySearch\Render
 */
class MySearchRenderer extends CustomizableClass
{
    use MySearchAuctionLotLoaderCreateTrait;
    use MySearchLinkBuilderCreateTrait;
    use MySearchLoaderCreateTrait;
    use SettingsManagerAwareTrait;
    use UserAlertSentLotManagerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $userId
     * @param int $accountId
     * @return string
     */
    public function makeEmailSearchResultsPlaceholder(int $userId, int $accountId): string
    {
        $output = <<<HTML
   <style>
       .title{
        border:1px solid lightgray;
        word-wrap: break-word;
        word-break: break-all;
        width: 360px;
        }
       .caption
       {
            border:1px solid lightgray;
       }
       .caption th
       {
            background-color:lightgray;
            color:white;
       }

       </style>
    <div class='heading'>Search Results</div>
    <table>
    <thead>
    <tr class='caption'>
    <th class='caption'>Title</th><th class='caption'>Link</th><th class='caption'>Count</th></tr>


HTML;

        $mySearches = $this->createMySearchLoader()->loadMailable($userId);
        $isSendOnce = $this->getSettingsManager()->getForMain(Constants\Setting::SEND_RESULTS_ONCE);
        $sentLotIds = $isSendOnce
            ? $this->createUserAlertSentLotManager()->loadSentLotsIdList($userId)
            : [];

        foreach ($mySearches as $mySearch) {
            $isExclude = $mySearch->MySearchExcludeClosed;
            $searchIds = $this->createMySearchAuctionLotLoader()->loadIds($mySearch, $sentLotIds, $isExclude);
            $count = count($searchIds);
            if (!$count) {
                continue;
            }
            $linkForSearch = $this->createMySearchLinkBuilder()->buildForEmail($mySearch, $accountId);
            $output .= <<<HTML
                <tr>
                 <td class='title' >{$mySearch->Title}</td>
                 <td class='caption' style = "text-align:center;" ><a href = '{$linkForSearch}'>Show Result</a></td>
                     <td class = 'caption' style = "text-align:center;">{$count}</td>
                     </tr>
HTML;
        }
        $output .= <<<HTML

       </table>

HTML;

        return $output;
    }
}
