<?php
/**
 * SAM-4714:Refactor Ask Question service
 * https://bidpath.atlassian.net/browse/SAM-4714
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2/6/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Feedback\AskQuestion;

/**
 * Trait AskQuestionServiceAwareTrait
 * @package Sam\Feedback\AskQuestion
 */
trait AskQuestionServiceAwareTrait
{
    protected ?AskQuestionService $askQuestionService = null;

    /**
     * @return AskQuestionService
     */
    protected function getAskQuestionService(): AskQuestionService
    {
        if ($this->askQuestionService === null) {
            $this->askQuestionService = AskQuestionService::new();
        }
        return $this->askQuestionService;
    }

    /**
     * @param AskQuestionService $askQuestionService
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setAskQuestionService(AskQuestionService $askQuestionService): static
    {
        $this->askQuestionService = $askQuestionService;
        return $this;
    }
}
