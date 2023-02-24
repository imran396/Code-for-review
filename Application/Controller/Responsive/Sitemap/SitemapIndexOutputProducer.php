<?php
/**
 * SAM-10192: Move alone end-points to controllers
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 25, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Sitemap;

use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Sitemap\Auctions\Manager;

/**
 * Class SitemapIndexOutputProducer
 * @package Sam\Application\Controller\Responsive\Sitemap
 */
class SitemapIndexOutputProducer extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use AccountLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function produce(?int $accountId): void
    {
        if ($this->cfg()->get('core->sitemap->enableSingleIndex')) {
            $output = Manager::new()->generate();
        } else {
            $account = $this->getAccountLoader()->load($accountId);
            if ($account) {
                $output = Manager::new()
                    ->filterAccountId($accountId)
                    ->generate();
            } else {
                $output = '<?xml version="1.0" encoding="UTF-8"?><notFound>No Account Found</notFound>';
            }
        }
        header('Content-type: text/xml');
        echo $output;
    }
}
