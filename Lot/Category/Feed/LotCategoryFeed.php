<?php
/**
 * SAM-9677: Refactor \Feed\CategoryGet
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 21, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Category\Feed;

use Sam\Core\Service\CustomizableClass;
use Sam\Infrastructure\Net\HttpCacheManagerCreateTrait;
use Sam\Lot\Category\Feed\Internal\Build\FeedMakerCreateTrait;
use Sam\Lot\Category\Feed\Internal\Cache\FileCacherCreateTrait;
use Sam\Lot\Category\Feed\Internal\Encode\EncoderCreateTrait;
use Sam\Lot\Category\Feed\Internal\Render\FeedRendererCreateTrait;

/**
 * Class LotCategoryFeed
 * @package Sam\Lot\Category\Feed
 */
class LotCategoryFeed extends CustomizableClass
{
    use EncoderCreateTrait;
    use FeedMakerCreateTrait;
    use FeedRendererCreateTrait;
    use FileCacherCreateTrait;
    use HttpCacheManagerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function render(array $requestData, int $cacheTtl = 300, string $encoding = 'UTF-8', bool $enableProfiling = false): void
    {
        $this->createFeedRenderer()->sendHeader($encoding);

        $output = '';
        $cache = $this->createFileCacher()->construct($requestData, $cacheTtl, $enableProfiling);
        if ($cache->isFresh()) {
            $this->createHttpCacheManager()->sendHeadersAndExitIfNotModified($cache->getModificationTime());
            $output = $cache->get();
        }

        if (!$output) {
            $output = $this->createFeedMaker()->make();

            $ts = microtime(true);
            $output = $this->createEncoder()->encode($output, $encoding);
            if ($enableProfiling) {
                log_debug(composeSuffix(['encoding value' => ((microtime(true) - $ts) * 1000) . 'ms']));
            }

            $cache->set($output);
        }

        $this->createFeedRenderer()->sendOutput($output);
    }
}
