<?php
/**
 * SAM-6584: Decouple observers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\UserShipping\Internal;

use Sam\Core\Address\Render\AddressRenderer;
use Sam\Observer\EntityObserverSubject;
use Sam\User\Log\Observe\UserLoggerBaseHandler;
use UserShipping;

/**
 * Class UserShippingLogger
 * @package Sam\Observer\UserShipping
 * @internal
 */
class UserShippingLogger extends UserLoggerBaseHandler
{
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
        /** @var UserShipping $userShipping */
        $userShipping = $subject->getEntity();
        return $userShipping->UserId;
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFields(EntityObserverSubject $subject): array
    {
        return [
            'ContactType' => 'Shipping Contact Type',
            'FirstName' => 'Shipping First Name',
            'LastName' => 'Shipping Last Name',
            'CompanyName' => 'Shipping Company Name',
            'Phone' => 'Shipping Phone',
            'Fax' => 'Shipping Fax',
            'Country' => 'Shipping Country',
            'Address' => 'Shipping Address',
            'Address2' => 'Shipping Address 2',
            'Address3' => 'Shipping Address 3',
            'City' => 'Shipping City',
            'State' => 'Shipping State/Province',
            'Zip' => 'Shipping Zip/Postal code',
        ];
    }

    /**
     * @inheritDoc
     */
    protected function treat(EntityObserverSubject $subject, string $property, bool $isOld = false): string
    {
        /** @var UserShipping $userShipping */
        $userShipping = $subject->getEntity();
        $value = $isOld
            ? $subject->getOldPropertyValue($property)
            : $userShipping->$property;
        $treatedValue = (string)$value;
        switch ($property) {
            case 'Country':
                $treatedValue = AddressRenderer::new()->countryName($treatedValue);
                break;

            case 'State':
                $country = $isOld && $subject->isPropertyModified('Country')
                    ? $subject->getOldPropertyValue('Country')
                    : $userShipping->Country;

                $treatedValue = AddressRenderer::new()->stateName($treatedValue, $country);
                break;
        }
        return $treatedValue;
    }
}
