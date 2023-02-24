<?php
/**
 * Common methods for a control
 * Refactor Live and Timed Lot Details pages of responsive side
 * @see https://bidpath.atlassian.net/browse/SAM-3241
 *
 * @copyright   2018 Bidpath, Inc.
 * @author      Maxim Lyubetskiy
 * @package     com.swb.sam2
 * @version     SVN: $Id$
 * @since       Jun 09, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Qform\Lot;

use RuntimeException;
use Sam\Qform\Lot\Details\Live\ViewContext;

/**
 * Trait GeneralControlTrait
 */
trait GeneralControlTrait
{
    private ?ViewContext $viewContext = null;
    private ?string $controlId = null;
    /**
     * @var int[]
     */
    private array $availableStates = [];

    /**
     * @return string|null
     */
    public function getControlId(): ?string
    {
        return $this->controlId;
    }

    /**
     * @param string $controlId
     * @return static
     */
    public function setControlId(string $controlId): static
    {
        $this->controlId = $controlId;
        return $this;
    }

    /**
     * @return ViewContext
     */
    public function getViewContext(): ViewContext
    {
        if ($this->viewContext === null) {
            throw new RuntimeException('ViewContext is invalid');
        }
        return $this->viewContext;
    }

    /**
     * @param ViewContext $viewContext
     * @return static
     */
    public function setViewContext(ViewContext $viewContext): static
    {
        $this->viewContext = $viewContext;
        return $this;
    }

    /**
     * @return int[]
     */
    public function getAvailableStates(): array
    {
        return $this->availableStates;
    }

    /**
     * @param int[] $availableStates
     * @return static
     */
    public function setAvailableStates(array $availableStates): static
    {
        $this->availableStates = $availableStates;
        return $this;
    }
}
