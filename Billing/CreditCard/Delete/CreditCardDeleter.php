<?php
/**
 * SAM-4726: Credit Card Deleter
 * https://bidpath.atlassian.net/browse/SAM-4726
 *
 * @author        Vahagn Hovsepyan
 * @since         Dec 21, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Billing\CreditCard\Delete;

use CreditCard;
use Sam\Billing\CreditCard\Load\CreditCardLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\CreditCard\CreditCardWriteRepositoryAwareTrait;

/**
 * Class CreditCardDeleter
 */
class CreditCardDeleter extends CustomizableClass
{
    use CreditCardLoaderAwareTrait;
    use CreditCardWriteRepositoryAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param CreditCard $creditCard
     * @param int $editorUserId
     */
    public function delete(CreditCard $creditCard, int $editorUserId): void
    {
        $creditCard->Active = false;
        $this->getCreditCardWriteRepository()->saveWithModifier($creditCard, $editorUserId);
    }

    /**
     * @param int $ccId
     * @param int $editorUserId
     */
    public function deleteById(int $ccId, int $editorUserId): void
    {
        $creditCard = $this->getCreditCardLoader()->load($ccId, true);
        if ($creditCard) {
            $this->delete($creditCard, $editorUserId);
        }
    }
}
