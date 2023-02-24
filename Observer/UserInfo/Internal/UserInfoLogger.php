<?php
/**
 * SAM-6584: Decouple observers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 18, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\UserInfo\Internal;

use Sam\Lang\ViewLanguage\Load\ViewLanguageLoaderAwareTrait;
use Sam\Observer\EntityObserverSubject;
use Sam\User\Identification\UserIdentificationTransformerAwareTrait;
use Sam\User\Log\Observe\UserLoggerBaseHandler;
use UserInfo;

/**
 * Class UserInfoLogger
 * @package Sam\Observer\UserInfo
 * @internal
 */
class UserInfoLogger extends UserLoggerBaseHandler
{
    use UserIdentificationTransformerAwareTrait;
    use ViewLanguageLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @inheritDoc
     */
    protected function getEntityUserId(EntityObserverSubject $subject): int
    {
        /** @var UserInfo $userInfo */
        $userInfo = $subject->getEntity();
        return $userInfo->UserId;
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFields(EntityObserverSubject $subject): array
    {
        return [
            'FirstName' => 'First Name',
            'LastName' => 'Last Name',
            'Phone' => 'Phone',
            'PhoneType' => 'Phone Type',
            'Identification' => 'Identification',
            'IdentificationType' => 'Identification Type',
            'CompanyName' => 'Company Name',
            'SendTextAlerts' => 'Send Text Alerts',
            'Resume' => 'Resume',
            'ViewLanguage' => 'View Language',
            'NewsLetter' => 'News Letter',
        ];
    }

    /**
     * Treat some values to be human readable (SendTextAlerts, NewsLetter, ViewLanguage)
     *
     * @inheritDoc
     */
    protected function treat(EntityObserverSubject $subject, string $property, bool $isOld = false): string
    {
        /** @var UserInfo $userInfo */
        $userInfo = $subject->getEntity();
        $value = $isOld
            ? $subject->getOldPropertyValue($property)
            : $userInfo->$property;
        $treatedValue = (string)$value;
        switch ($property) {
            case 'NewsLetter':
            case 'SendTextAlerts':
                $treatedValue = $this->treatBoolean((bool)$value);
                break;
            case 'ViewLanguage':
                $viewLanguage = $this->getViewLanguageLoader()
                    ->load((int)$value, $this->cfg()->get('core->portal->mainAccountId'));
                $treatedValue = $viewLanguage->Name ?? '';
                break;
            case 'Identification':
                $identificationType = $isOld && $subject->isPropertyModified('IdentificationType')
                    ? $subject->getOldPropertyValue('IdentificationType')
                    : $userInfo->IdentificationType;

                $treatedValue = $this->getUserIdentificationTransformer()->render($treatedValue, $identificationType, true);
                break;
        }
        return $treatedValue;
    }
}
