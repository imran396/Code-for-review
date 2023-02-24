<?php
/**
 * Helps to modify UserInfo->Note
 *
 * SAM-3893: Refactor auction bidder related functionality
 *
 * @author        Igors Kotlevskis
 * @since         Dec 4, 2017
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\User\Info;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Html\HtmlRenderer;
use Sam\Date\CurrentDateTrait;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Storage\WriteRepository\Entity\UserInfo\UserInfoWriteRepositoryAwareTrait;
use UserBilling;
use UserInfo;

/**
 * Class NoteManager
 * @package Sam\User\Info
 */
class UserInfoNoteManager extends CustomizableClass
{
    use BlockCipherProviderCreateTrait;
    use CurrentDateTrait;
    use UserInfoWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Add user note about failed AuthOrCapture operation.
     * It adds CC info, when $userBilling passed.
     * @param UserInfo $userInfo
     * @param string $message
     * @param int $auctionId
     * @param UserBilling $userBilling
     * @param int $editorUserId
     * @return UserInfo
     */
    public function applyAuctionRegistration(
        UserInfo $userInfo,
        string $message,
        int $auctionId,
        UserBilling $userBilling,
        int $editorUserId
    ): UserInfo {
        $dateFormatted = $this->getCurrentDateUtc()->format('m/d/Y H:i');
        $ccInfo = '';
        if ($userBilling->CcNumber) {
            $ccNumberPart = mb_substr($this->createBlockCipherProvider()->construct()->decrypt($userBilling->CcNumber), -4);
            $ccInfo = " CC ({$ccNumberPart} {$userBilling->CcExpDate})";
        }
        $userInfo->Note .= HtmlRenderer::new()->replaceTags(
            $dateFormatted . ":{$ccInfo}"
            . " on auction registration ({$auctionId})"
            . " failed: {$message}\n"
        );
        $this->getUserInfoWriteRepository()->saveWithModifier($userInfo, $editorUserId);
        return $userInfo;
    }
}
