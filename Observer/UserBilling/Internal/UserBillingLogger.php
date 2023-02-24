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

namespace Sam\Observer\UserBilling\Internal;

use Sam\Billing\CreditCard\Load\CreditCardLoaderAwareTrait;
use Sam\Core\Address\Render\AddressRenderer;
use Sam\Observer\EntityObserverSubject;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\User\Log\Observe\UserLoggerBaseHandler;
use UserBilling;

/**
 * Class UserLogger
 * @package Sam\Observer\UserBilling
 * @internal
 */
class UserBillingLogger extends UserLoggerBaseHandler
{
    use BlockCipherProviderCreateTrait;
    use CreditCardLoaderAwareTrait;

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
        /** @var UserBilling $userBilling */
        $userBilling = $subject->getEntity();
        return $userBilling->UserId;
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFields(EntityObserverSubject $subject): array
    {
        return [
            'Address' => 'Billing Address',
            'Address2' => 'Billing Address 2',
            'Address3' => 'Billing Address 3',
            'CcExpDate' => 'Billing CC Exp. Date',
            'CcNumber' => 'Billing CC Number',
            'CcType' => 'Billing CC Type',
            'City' => 'Billing City',
            'CompanyName' => 'Billing Company Name',
            'ContactType' => 'Billing Contact Type',
            'Country' => 'Billing Country',
            'Fax' => 'Billing Fax',
            'FirstName' => 'Billing First Name',
            'LastName' => 'Billing Last Name',
            'Phone' => 'Billing Phone',
            'State' => 'Billing State/Province',
            'Zip' => 'Billing Zip/Postal code',
        ];
    }

    /**
     * Treat some values to be human readable (Country, State, CcType, CcNumber)
     *
     * @inheritDoc
     */
    protected function treat(EntityObserverSubject $subject, string $property, bool $isOld = false): string
    {
        /** @var UserBilling $userBilling */
        $userBilling = $subject->getEntity();
        $value = $isOld
            ? $subject->getOldPropertyValue($property)
            : $userBilling->$property;

        $treatedValue = (string)$value;
        switch ($property) {
            case 'CcNumber':
                $treatedValue = substr($this->createBlockCipherProvider()->construct()->decrypt($treatedValue), -4);
                if ($treatedValue) {
                    $treatedValue = 'xxxx' . $treatedValue;
                }
                break;

            case 'CcType':
                $ccType = $this->getCreditCardLoader()->load((int)$value);
                $treatedValue = $ccType->Name ?? '';
                break;

            case 'Country':
                $treatedValue = AddressRenderer::new()->countryName($treatedValue);
                break;

            case 'State':
                $country = $isOld && $subject->isPropertyModified('Country')
                    ? $subject->getOldPropertyValue('Country')
                    : $userBilling->Country;
                $treatedValue = AddressRenderer::new()->stateName($treatedValue, $country);
                break;
        }
        return $treatedValue;
    }
}
