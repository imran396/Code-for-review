<?php
/**
 * SAM-4051: Bidder Info Renderer
 * https://bidpath.atlassian.net/browse/SAM-4051
 *
 * @author        Imran Rahman
 * @version       SVN: $Id: $
 * @since         Jan 26, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 *
 */

namespace Sam\Bidder\BidderInfo;

use Sam\Core\Service\CustomizableClass;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;
use Sam\User\Flag\UserFlaggingAwareTrait;
use Sam\User\Render\UserRendererAwareTrait;
use User;

/**
 * Class BidderInfoRenderer
 * @package Sam\Bidder\BidderInfo
 */
class BidderInfoRenderer extends CustomizableClass
{
    use AuctionAwareTrait;
    use AuctionBidderLoaderAwareTrait;
    use BidderNumPaddingAwareTrait;
    use SettingsManagerAwareTrait;
    use TranslatorAwareTrait;
    use UserAwareTrait;
    use UserFlaggingAwareTrait;
    use UserRendererAwareTrait;

    protected ?string $displayBidderInfo = null;
    protected bool $isFloorBlank = false;
    protected bool $isReadOnlyDb = false;
    protected bool $isFlagging = false;
    protected bool $isTranslation = false;
    protected bool $isMaskUsernameIfEmail = false;
    protected bool $hasEditorUserAdminRole = false;
    protected bool $hasEditorUserPrivilegeForCrossAccount = false;
    protected ?int $editorUserId = null;
    protected ?int $systemAccountId = null;
    protected ?int $languageId = null;
    protected ?int $editorUserAccountId = null;
    protected ?int $lotItemAccountId = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Get bidder info that should be displayed
     * @return string
     */
    public function render(): string
    {
        // $userId can be "0" in case of floor bid
        $targetBidderUser = $this->getUser();
        if ($targetBidderUser) {
            return $this->renderOnlineBidder($targetBidderUser);
        }

        return $this->renderFloorBidder();
    }

    protected function renderOnlineBidder(User $targetBidderUser): string
    {
        $displayBidderInfo = $this->getDisplayBidderInfo();
        switch ($displayBidderInfo) {
            case Constants\SettingAuction::DBI_NUMBER:
                $output = $this->detectBidderNum();
                break;

            case Constants\SettingAuction::DBI_USERNAME:
                $output = $targetBidderUser->Username;
                if ($this->shouldMaskUsernameIfAlikeEmail($output, $targetBidderUser->Id)) {
                    $output = $this->getUserRenderer()->maskUsernameIfAlikeEmail($output);
                }
                break;

            case Constants\SettingAuction::DBI_COMPANY_NAME:
                $companyName = trim($this->getUserInfo()->CompanyName ?? '');
                $output = $companyName ?: $this->detectBidderNum();
                break;

            default:
                log_error("Unknown value \"{$displayBidderInfo}\" of DISPLAY_BIDDER_INFO setting" . composeSuffix($this->logData()));
                return '';
        }

        $output .= $this->renderFlagInfo();
        return $output;
    }

    protected function renderFloorBidder(): string
    {
        $langFloorBidder = $this->isTranslation()
            ? $this->getTranslator()->translate('BIDDERCLIENT_FLOORBIDDER', 'bidderclient')
            : "floor bidder";
        $output = $this->isFloorBlank() ? '' : $langFloorBidder;
        return $output;
    }

    /**
     * Find auction bidder number
     * @return string
     */
    protected function detectBidderNum(): string
    {
        $auctionId = $this->getAuctionId();
        if ($auctionId) {
            $auctionBidder = $this->getAuctionBidderLoader()
                ->load($this->getUserId(), $auctionId, $this->isReadOnlyDb());
            if ($auctionBidder) {
                return $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum);
            }
        }
        return '';
    }

    /**
     * @return string
     */
    protected function renderFlagInfo(): string
    {
        $user = $this->getUser();
        if (!$user) {
            return '';
        }

        $output = '';
        $flags = [];
        if ($user->isDeleted()) {
            $langDeleted = $this->isTranslation()
                ? $this->getTranslator()->translate('USER_DELETED', 'user')
                : "Deleted";
            $flags[] = $langDeleted;
        }
        if ($this->isFlagging()) {
            $flag = $this->getUserFlagging()->detectFlag($user->Id, $user->AccountId);
            if ($flag) {
                $flags[] = UserPureRenderer::new()->makeFlagAbbr($flag);
            }
        }
        if ($flags) {
            $output .= ' (' . implode(', ', $flags) . ')';
        }
        return $output;
    }

    /**
     * Check, if username must be redacted in case it contains e-mail.
     * E-mail format is not checked by this function, this verification is delegated to masking function.
     * @param string $username
     * @param int $targetUserId
     * @return bool
     */
    protected function shouldMaskUsernameIfAlikeEmail(string $username, int $targetUserId): bool
    {
        // Don't mask, if masking requirement is disabled.
        if (!$this->isMaskUsernameIfEmail) {
            return false;
        }

        // Don't mask own username.
        if ($this->editorUserId === $targetUserId) {
            return false;
        }

        // Don't mask, if editor is cross-account admin.
        if ($this->hasEditorUserPrivilegeForCrossAccount) {
            return false;
        }

        // Don't mask, if editor is admin.
        if (
            $this->hasEditorUserAdminRole
            && $this->editorUserAccountId // JIC, not missed
            && $this->editorUserAccountId === $this->lotItemAccountId
        ) {
            return false;
        }

        return true;
    }

    /**
     * Helper method for getting bidder info
     *
     * @param int $userId
     * @param int $auctionId
     * @param string|null $displayBidderInfo
     * @param bool $isTranslation
     * @param bool $isFloorBlank
     * @param bool $isFlagging
     * @param bool $isReadOnlyDb
     * @param bool $isMaskUsernameIfEmail
     * @param bool $hasEditorUserAdminRole
     * @param int|null $editorUserAccountId
     * @param int|null $lotItemAccountId
     * @param int|null $languageId
     * @return string
     */
    public function renderInfo(
        int $userId,
        int $auctionId,
        ?string $displayBidderInfo = null,
        bool $isTranslation = true,
        bool $isFloorBlank = true,
        bool $isFlagging = true,
        bool $isReadOnlyDb = false,
        bool $isMaskUsernameIfEmail = false,
        bool $hasEditorUserAdminRole = false,
        ?int $editorUserAccountId = null,
        ?int $lotItemAccountId = null,
        ?int $languageId = null
    ): string {
        $output = $this
            ->enableFlagging($isFlagging)
            ->enableFloorBlank($isFloorBlank)
            ->enableMaskUsernameIfEmail($isMaskUsernameIfEmail)
            ->enableReadOnlyDb($isReadOnlyDb)
            ->enableTranslation($isTranslation)
            ->setAuctionId($auctionId)
            ->setDisplayBidderInfo($displayBidderInfo)
            ->setUserId($userId)
            ->enableEditorUserAdminRole($hasEditorUserAdminRole)
            ->setEditorUserAccountId($editorUserAccountId)
            ->setLotItemAccountId($lotItemAccountId)
            ->setLanguageId($languageId)
            ->render();
        return $output;
    }

    /**
     * Helper for rendering at rtb consoles of admin site (Clerk, Auctioneer)
     * @param int|null $userId
     * @param int|null $auctionId
     * @return string
     */
    public function renderForAdminRtb(?int $userId, ?int $auctionId): string
    {
        $output = $this
            ->enableFloorBlank(true)
            ->enableReadOnlyDb(true)
            ->enableTranslation(true)
            ->setAuctionId($auctionId)
            ->setUserId($userId)
            ->render();
        return $output;
    }

    /**
     * Helper for rendering at rtb consoles of public site (Bidder, Viewer)
     * @param int|null $userId
     * @param int|null $auctionId
     * @return string
     */
    public function renderForPublicRtb(?int $userId, ?int $auctionId): string
    {
        $output = $this
            ->enableFloorBlank(true)
            ->enableMaskUsernameIfEmail(true)
            ->enableReadOnlyDb(true)
            ->enableTranslation(true)
            ->setAuctionId($auctionId)
            ->setUserId($userId)
            ->render();
        return $output;
    }

    /**
     * @return string
     */
    protected function getDisplayBidderInfo(): string
    {
        if ($this->displayBidderInfo === null) {
            $auction = $this->getAuction();
            if (!$auction) {
                log_error(
                    "Available auction not found, when rendering bidder info"
                    . composeSuffix(['a' => $this->getAuctionId()])
                );
                return '';
            }
            $this->displayBidderInfo = (string)$this->getSettingsManager()
                ->get(Constants\Setting::DISPLAY_BIDDER_INFO, $auction->AccountId);
        }
        return $this->displayBidderInfo;
    }

    protected function logData(): array
    {
        return [
            'u' => $this->getUserId(),
            'a' => $this->getAuctionId(),
            'displayBidderInfo' => $this->displayBidderInfo,
            'isTranslation' => $this->isTranslation,
            'isFloorBlank' => $this->isFloorBlank,
            'isFlagging' => $this->isFlagging,
            'isMaskUsernameIfEmail' => $this->isMaskUsernameIfEmail,
            'hasEditorUserAdminRole' => $this->hasEditorUserAdminRole,
            'editorUserAccountId' => $this->editorUserAccountId,
            'lotItemAccountId' => $this->lotItemAccountId,
            'systemAccountId' => $this->systemAccountId,
            'languageId' => $this->languageId,
            'isReadOnlyDb' => $this->isReadOnlyDb,
        ];
    }

    /**
     * @param string|null $displayBidderInfo
     * @return static
     */
    public function setDisplayBidderInfo(?string $displayBidderInfo): static
    {
        $this->displayBidderInfo = Cast::toString($displayBidderInfo, Constants\SettingAuction::DISPLAY_BIDDER_INFOS);
        return $this;
    }

    /**
     * @param bool $isFloorBlank
     * @return static
     */
    public function enableFloorBlank(bool $isFloorBlank): static
    {
        $this->isFloorBlank = $isFloorBlank;
        return $this;
    }

    /**
     * @param bool $enable
     * @return static
     */
    public function enableReadOnlyDb(bool $enable): static
    {
        $this->isReadOnlyDb = $enable;
        return $this;
    }

    /**
     * @param bool $isFlagging
     * @return static
     */
    public function enableFlagging(bool $isFlagging): static
    {
        $this->isFlagging = $isFlagging;
        return $this;
    }

    /**
     * @param bool $isTranslation
     * @return static
     */
    public function enableTranslation(bool $isTranslation): static
    {
        $this->isTranslation = $isTranslation;
        return $this;
    }

    public function enableMaskUsernameIfEmail(bool $isMask): static
    {
        $this->isMaskUsernameIfEmail = $isMask;
        return $this;
    }

    public function enableEditorUserAdminRole(bool $hasAdminRole): static
    {
        $this->hasEditorUserAdminRole = $hasAdminRole;
        return $this;
    }

    public function enableEditorUserPrivilegeForCrossAccount(bool $hasPrivilegeForCrossAccount): static
    {
        $this->hasEditorUserPrivilegeForCrossAccount = $hasPrivilegeForCrossAccount;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFloorBlank(): bool
    {
        return $this->isFloorBlank;
    }

    /**
     * @return bool
     */
    public function isReadOnlyDb(): bool
    {
        return $this->isReadOnlyDb;
    }

    /**
     * @return bool
     */
    public function isFlagging(): bool
    {
        return $this->isFlagging;
    }

    /**
     * @return bool
     */
    public function isTranslation(): bool
    {
        return $this->isTranslation;
    }

    public function setEditorUserId(?int $editorUserId): static
    {
        $this->editorUserId = $editorUserId;
        return $this;
    }

    public function setLanguageId(int $languageId): static
    {
        $this->languageId = $languageId;
        return $this;
    }

    public function setSystemAccountId(int $accountId): static
    {
        $this->systemAccountId = $accountId;
        return $this;
    }

    public function setEditorUserAccountId(?int $accountId): static
    {
        $this->editorUserAccountId = $accountId;
        return $this;
    }

    public function setLotItemAccountId(?int $accountId): static
    {
        $this->lotItemAccountId = $accountId;
        return $this;
    }
}
