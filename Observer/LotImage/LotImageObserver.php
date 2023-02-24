<?php
/**
 * Observer for  Lot Images
 *
 * SAM-1524: Trigger image pre-caching
 *
 * @author        Pyotr Vorobyov
 * @package       com.swb.sam2
 * @version       SVN: $Id$
 * @since         Jul 17, 2014
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\LotImage;

use LotImage;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityObserverHelperCreateTrait;
use Sam\Observer\LotImage\Internal\LotImageCacheHandler;
use SplObserver;
use SplSubject;

/**
 * Class LotImageObserver
 * @package Sam\Observer\LotImage
 */
class LotImageObserver extends CustomizableClass implements SplObserver
{
    use EntityObserverHelperCreateTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param SplSubject $subject
     */
    public function update(SplSubject $subject): void
    {
        if (!$subject instanceof LotImage) {
            log_warning(composeLogData(['Subject not instance of LotImage' => get_class($subject)]));
            return;
        }
        $handlers = [
            LotImageCacheHandler::new()
        ];
        $this->createEntityObserverHelper()->runHandlers($handlers, $subject);
    }
}
