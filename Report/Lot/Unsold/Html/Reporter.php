<?php
/**
 * SAM-4687: Refactor "Unsold Lots" report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/28/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * IMPORTANT NOTE: Report any changes of format to your manager and in the ticket you are working on!
 * This might include adding, changing, or moving columns, modifying header names, modifying data or data format
 */

namespace Sam\Report\Lot\Unsold\Html;

use Sam\Core\Filter\Entity\FilterAuctionAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Report\Lot\Unsold\Base\DataLoaderAwareTrait;
use Sam\Report\Lot\Unsold\Base\ResultFieldsAwareTrait;

/**
 * Class Reporter
 * @package Sam\Report\Lot\Unsold
 */
class Reporter extends CustomizableClass
{
    use DataLoaderAwareTrait;
    use FilterAuctionAwareTrait;
    use ResultFieldsAwareTrait;

    /** @var Renderer|null */
    protected ?Renderer $renderer = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return string
     */
    public function output(): string
    {
        $rows = $this->getDataLoader()->load();
        $auctionRows = $this->getDataLoader()->loadAuctionData();
        $output = $this->getRenderer()->output($this->getResultFields(), $rows, $auctionRows);
        return $output;
    }

    /**
     * @return Renderer
     */
    public function getRenderer(): Renderer
    {
        if ($this->renderer === null) {
            $this->renderer = Renderer::new();
        }
        return $this->renderer;
    }

    /**
     * @param Renderer $renderer
     * @return static
     */
    public function setRenderer(Renderer $renderer): static
    {
        $this->renderer = $renderer;
        return $this;
    }
}
