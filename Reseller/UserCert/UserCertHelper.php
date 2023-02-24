<?php

namespace Sam\Reseller\UserCert;

use DateInterval;
use Sam\Application\Url\Build\Config\User\AnySingleUserUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\ReadRepository\Entity\UserInfo\UserInfoReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\UserInfo\UserInfoWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Privilege\Validate\AdminPrivilegeCheckerAwareTrait;
use User;
use UserInfo;

/**
 * Different functionality related to reseller certificates linked to user
 * SAM-2428: Bidonfusion - Reseller certificate tracking changes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Sep 09, 2014
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */
class UserCertHelper extends CustomizableClass
{
    use AdminPrivilegeCheckerAwareTrait;
    use ConfigRepositoryAwareTrait;
    use CurrentDateTrait;
    use EditorUserAwareTrait;
    use ResellerUserCertUploaderAwareTrait;
    use UrlBuilderAwareTrait;
    use UserInfoReadRepositoryCreateTrait;
    use UserInfoWriteRepositoryAwareTrait;
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
     * Return url for certificate downloading
     * @param int|null $userId
     * @return string
     */
    public function buildUrl(?int $userId): string
    {
        if (!$userId) {
            return '';
        }
        $url = $this->getUrlBuilder()->build(
            AnySingleUserUrlConfig::new()->forWeb(
                Constants\Url::P_DOWNLOADS_RESELLER_CERT_DOWNLOAD_FOR_USER,
                $userId
            )
        );
        return $url;
    }

    /**
     * Return root path for certificate file with passed name
     * @param string $fileName
     * @return string
     */
    public function getFileRootPath(string $fileName): string
    {
        $fileRootPath = path()->uploadReseller()
            . $this->cfg()->get('core->user->reseller->userCertUploadDir')
            . '/' . $fileName;
        return $fileRootPath;
    }

    /**
     * Return relative path to certificate file
     * @param string $fileName
     * @return string
     */
    public function getFilePath(string $fileName): string
    {
        $filePath = substr($this->getFileRootPath($fileName), strlen(path()->sysRoot()));
        return $filePath;
    }

    /**
     * Check if certificate file is accessible for passed user
     * @param User $owner whose certificate to check
     * @param User|null $me whose access rights are checked
     * @return bool
     */
    public function isAllowedDownload(User $owner, User $me = null): bool
    {
        $isAllowed = false;
        if ($me) {
            if ($owner->Id === $me->Id) {    // owner allowed
                $isAllowed = true;
            } else {
                $isAllowed = $this->isAllowedDelete($owner, $me);
            }
        }
        return $isAllowed;
    }

    /**
     * Check if certificate file is allowed to delete for passed user
     * @param User $owner whose certificate to check
     * @param User|null $me whose access rights are checked
     * @return bool
     */
    public function isAllowedDelete(User $owner, User $me = null): bool
    {
        $isAllowed = false;
        if ($me) {
            $checker = $this->getAdminPrivilegeChecker()->initByUser($me);
            if ($checker->hasPrivilegeForManageUsers()) {
                if ($this->cfg()->get('core->portal->enabled')) {
                    if (
                        $checker->hasPrivilegeForSuperadmin()
                        || $me->AccountId === $owner->AccountId
                    ) {
                        $isAllowed = true;
                    }
                } else {
                    $isAllowed = true;
                }
            }
            // We allow to delete own certificate, if it is expired or unapproved
            if ($owner->Id === $me->Id) {
                $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($owner->Id);
                if (!$this->isActualAndApproved($userInfo)) {
                    $isAllowed = true;
                }
            }
        }
        return $isAllowed;
    }

    /**
     * Check if reseller certificate is approved and not expired
     * @param UserInfo|null $userInfo
     * @return bool
     */
    public function isActualAndApproved(?UserInfo $userInfo): bool
    {
        $isApproved = false;
        if (
            $userInfo
            && $userInfo->ResellerCertExpiration
        ) {
            $expirationDate = clone $userInfo->ResellerCertExpiration;
            $currentDate = $this->getCurrentDateSys();
            $isApproved = $userInfo->ResellerCertFile !== ''
                && $userInfo->ResellerCertApproved
                && $expirationDate->add(new DateInterval('P1D')) > $currentDate;
        }
        return $isApproved;
    }

    /**
     * Check if reseller certificate of user is approved and not expired
     * @param int $userId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function isActualAndApprovedByUserId(int $userId, bool $isReadOnlyDb = false): bool
    {
        $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($userId, $isReadOnlyDb);
        return $this->isActualAndApproved($userInfo);
    }

    /**
     * Return array of UserInfo objects, which have approved reseller certificate with expired date
     * @param int|null $limit
     * @return UserInfo[]
     */
    public function getApprovedAndExpired(?int $limit = null): array
    {
        $currentDateIso = $this->getCurrentDateSys()->format('Y-m-d');
        $userRepository = $this->createUserInfoReadRepository()
            ->joinUserFilterUserStatusId(Constants\User::AVAILABLE_USER_STATUSES)
            ->inlineCondition('CHAR_LENGTH(ui.reseller_cert_file) > 0')
            ->filterResellerCertExpirationLess($currentDateIso)
            ->filterResellerCertApproved(true);
        if ($limit) {
            $userRepository->limit($limit);
        }
        $userInfos = $userRepository->loadEntities();
        return $userInfos;
    }

    /**
     * Check if certificate upload/update should be requested at auction registration process
     * @param int|null $userId null for anonymous user results with false
     * @return bool
     */
    public function requiredOnAuctionRegistration(?int $userId): bool
    {
        if (!$userId) {
            return false;
        }

        $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($userId);
        if (
            $userInfo->ResellerCertExpiration
            && $userInfo->ResellerCertFile !== ''
        ) {
            $expirationDate = clone $userInfo->ResellerCertExpiration;
            $isRequired = $expirationDate->add(new DateInterval('P1D')) < $this->getCurrentDateSys();
        } else {
            $isRequired = true;
        }
        return $isRequired;
    }

    /**
     * Try to delete reseller certificate file by currently authenticated user
     * @param int|null $userId Owner of certificate file, null results to false
     * @return bool
     */
    public function deleteByMe(?int $userId): bool
    {
        if (!$userId) {
            return false;
        }
        $owner = $this->getUserLoader()->load($userId, true);
        $editorUser = $this->getEditorUser();
        if (
            $owner
            && $this->isAllowedDelete($owner, $editorUser)
        ) {
            return $this->delete($userId);
        }
        return false;
    }

    /**
     * Unconditional reseller certificate file deletion
     * @param int $userId Owner of certificate file
     * @return bool
     */
    public function delete(int $userId): bool
    {
        $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($userId, true);
        if ($userInfo->ResellerCertFile !== '') {
            $userCertUploader = $this->getResellerUserCertUploader()->setUserId($userId);
            $userCertUploader->deleteCurrent();

            $userInfo->ResellerCertFile = '';
            $userInfo->ResellerCertApproved = false;
            $userInfo->ResellerCertExpiration = null;
            $this->getUserInfoWriteRepository()->saveWithModifier($userInfo, $this->getEditorUserId());
            return true;
        }
        return false;
    }
}
