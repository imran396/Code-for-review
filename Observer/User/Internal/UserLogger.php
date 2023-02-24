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

namespace Sam\Observer\User\Internal;

use Sam\Observer\EntityObserverSubject;
use Sam\User\Log\Observe\UserLoggerBaseHandler;
use User;

/**
 * Class UserLogger
 * @package Sam\Observer\User
 * @internal
 */
class UserLogger extends UserLoggerBaseHandler
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
        /** @var User $user */
        $user = $subject->getEntity();
        return $user->Id;
    }

    /**
     * @inheritDoc
     */
    protected function getTrackedFields(EntityObserverSubject $subject): array
    {
        return [
            'Email' => 'E-mail',
            'Pword' => 'Password',
        ];
    }

    /**
     * Treat some values to be human readable (Pword)
     *
     * @inheritDoc
     */
    protected function treat(EntityObserverSubject $subject, string $property, bool $isOld = false): string
    {
        /** @var User $user */
        $user = $subject->getEntity();
        $value = $isOld
            ? $subject->getOldPropertyValue($property)
            : $user->$property;
        $treatedValue = (string)$value;
        if (
            $property === 'Pword'
            && !empty($treatedValue)
        ) {
            $treatedValue = '***';
        }
        return $treatedValue;
    }
}
