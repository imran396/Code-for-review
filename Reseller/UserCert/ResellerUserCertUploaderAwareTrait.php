<?php

namespace Sam\Reseller\UserCert;

/**
 * Trait UserCertUploaderAwareTrait
 * @package Sam\Reseller\UserCert
 */
trait ResellerUserCertUploaderAwareTrait
{
    /**
     * @var UserCertUploader|null
     */
    protected ?UserCertUploader $resellerUserCertUploader = null;

    /**
     * @return UserCertUploader
     */
    protected function getResellerUserCertUploader(): UserCertUploader
    {
        if ($this->resellerUserCertUploader === null) {
            $this->resellerUserCertUploader = UserCertUploader::new();
        }
        return $this->resellerUserCertUploader;
    }

    /**
     * @param UserCertUploader $resellerUserCardUploader
     * @return static
     * @internal
     */
    public function setResellerUserCertUploader(UserCertUploader $resellerUserCardUploader): static
    {
        $this->resellerUserCertUploader = $resellerUserCardUploader;
        return $this;
    }
}
