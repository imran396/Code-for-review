<?php
/**
 * SAM-4924: Report a problem test link adjustment related to rtbd public path
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           03.06.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Feedback\LiveSale;

/**
 * Trait LiveSaleFeedbackServiceAwareTrait
 * @package Sam\Feedback\LiveSale
 */
trait LiveSaleFeedbackServiceAwareTrait
{
    /**
     * @var LiveSaleFeedbackService|null
     */
    protected ?LiveSaleFeedbackService $liveSaleFeedbackService = null;

    /**
     * @return LiveSaleFeedbackService
     */
    protected function getLiveSaleFeedbackService(): LiveSaleFeedbackService
    {
        if ($this->liveSaleFeedbackService === null) {
            $this->liveSaleFeedbackService = LiveSaleFeedbackService::new();
        }
        return $this->liveSaleFeedbackService;
    }

    /**
     * @param LiveSaleFeedbackService $liveSaleFeedbackService
     * @return static
     */
    public function setLiveSaleFeedbackService(LiveSaleFeedbackService $liveSaleFeedbackService): static
    {
        $this->liveSaleFeedbackService = $liveSaleFeedbackService;
        return $this;
    }
}
