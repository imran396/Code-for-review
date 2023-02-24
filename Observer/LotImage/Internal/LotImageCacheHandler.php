<?php
/**
 * SAM-6584: Decouple observers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 17, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\LotImage\Internal;

use LotImage;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Image\Queue\LotImageQueueCreateTrait;
use Sam\Observer\EntityCreationObserverHandlerInterface;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class LotImageCacheHandler
 * @paqckage Sam\Observer\LotImage
 * @internal
 */
class LotImageCacheHandler extends CustomizableClass
    implements EntityCreationObserverHandlerInterface, EntityUpdateObserverHandlerInterface
{
    use LotImageQueueCreateTrait;
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
     * @inheritDoc
     */
    public function onCreate(EntityObserverSubject $subject): void
    {
        /** @var LotImage $lotImage */
        $lotImage = $subject->getEntity();
        $editorUserId = $this->getUserLoader()->loadSystemUserId();
        $this->createLotImageQueue()->addToCached($lotImage->Id, $editorUserId);
    }

    /**
     * @inheritDoc
     */
    public function onUpdate(EntityObserverSubject $subject): void
    {
        /** @var LotImage $lotImage */
        $lotImage = $subject->getEntity();
        $editorUserId = $this->getUserLoader()->loadSystemUserId();
        $this->createLotImageQueue()->addToCached($lotImage->Id, $editorUserId);
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        /** @var LotImage $lotImage */
        $lotImage = $subject->getEntity();
        return $lotImage->Size === 0
            || $subject->isAnyPropertyModified(['ImageLink', 'Size']);
    }
}
