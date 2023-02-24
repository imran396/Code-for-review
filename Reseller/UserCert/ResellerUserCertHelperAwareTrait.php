<?php

namespace Sam\Reseller\UserCert;

/**
 * Trait UserCertHelperAwareTrait
 * @package Sam\Reseller\UserCert
 */
trait ResellerUserCertHelperAwareTrait
{
    /**
     * @var UserCertHelper|null
     */
    protected ?UserCertHelper $resellerUserCertHelper = null;

    /**
     * @return UserCertHelper
     */
    protected function getResellerUserCertHelper(): UserCertHelper
    {
        if ($this->resellerUserCertHelper === null) {
            $this->resellerUserCertHelper = UserCertHelper::new();
        }
        return $this->resellerUserCertHelper;
    }

    /**
     * @param UserCertHelper $resellerUserCardHelper
     * @return static
     * @internal
     */
    public function setResellerUserCertHelper(UserCertHelper $resellerUserCardHelper): static
    {
        $this->resellerUserCertHelper = $resellerUserCardHelper;
        return $this;
    }
}
