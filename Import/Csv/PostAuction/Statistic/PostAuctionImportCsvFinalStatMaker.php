<?php
/**
 * SAM-9360: Refactor \Lot_PostCsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 25, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\PostAuction\Statistic;

use Sam\Application\Url\Build\Config\User\AdminUserEditUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Translation\AdminTranslatorAwareTrait;
use Sam\User\Flag\UserFlaggingAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use User;

/**
 * This class is responsible for generating messages about the result of the post auction import process
 *
 * Class PostAuctionImportCsvFinalStatMaker
 * @package Sam\Import\Csv\PostAuction
 */
class PostAuctionImportCsvFinalStatMaker extends CustomizableClass
{
    use AdminTranslatorAwareTrait;
    use AuctionLoaderAwareTrait;
    use UrlBuilderAwareTrait;
    use UserFlaggingAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     *  Make a messages based on the collected statistics
     *
     * @param PostAuctionImportCsvProcessStatistic $statistic
     * @param int $auctionId
     * @return array
     */
    public function make(PostAuctionImportCsvProcessStatistic $statistic, int $auctionId): array
    {
        $translator = $this->getAdminTranslator();
        if (!$statistic->processedLotsQuantity) {
            return [
                [
                    'type' => 'warning',
                    'text' => $translator->trans('import.csv.post_auction.stat.no_items_processed', [], 'admin_message')
                ]
            ];
        }

        if ($statistic->flaggedUsers) {
            $auction = $this->getAuctionLoader()->load($auctionId, true);
            if (!$auction) {
                log_error('Auction not found' . composeLogData(['a' => $auctionId]));
                return [];
            }
            return [$this->makeFlaggedUserMessage($statistic->flaggedUsers, $auction->AccountId)];
        }

        return [
            [
                'type' => 'success',
                'text' => $translator->trans('import.csv.post_auction.stat.updated_successfully', [], 'admin_message')
            ]
        ];
    }

    /**
     * @param array $flaggedUsers
     * @param int $entityAccountId
     * @return array
     */
    protected function makeFlaggedUserMessage(array $flaggedUsers, int $entityAccountId): array
    {
        $userIdLinks = array_map(
            function (User $user) use ($entityAccountId) {
                return $this->makeUserLink($user, $entityAccountId);
            },
            $flaggedUsers
        );
        return [
            'type' => 'warning',
            'text' => $this->getAdminTranslator()->trans(
                'import.csv.post_auction.stat.flagged_user_detected',
                [
                    'userIdList' => implode(', ', $userIdLinks)
                ],
                'admin_message'
            )
        ];
    }

    /**
     * @param User $user
     * @param int $entityAccountId
     * @return string
     */
    protected function makeUserLink(User $user, int $entityAccountId): string
    {
        $userFlag = $this->getUserFlagging()->detectFlagByUser($user, $entityAccountId);
        $flagName = UserPureRenderer::new()->makeFlag($userFlag);
        $flagAbbr = UserPureRenderer::new()->makeFlagAbbr($userFlag);
        $userEditUrl = $this->getUrlBuilder()->build(
            AdminUserEditUrlConfig::new()->forWeb($user->Id)
        );

        $link = <<<HTML
<a href="{$userEditUrl}" target="_blank">{$user->Id} (<span title="{$flagName}">{$flagAbbr}</span>)</a>
HTML;
        return $link;
    }
}
