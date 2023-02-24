<?php
/**
 * Replace feed content with custom replacement values
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         May 29, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Core\Feed;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\FeedCustomReplacement\FeedCustomReplacementReadRepositoryCreateTrait;

/**
 * Class CustomReplacementTool
 * @package Sam\Details
 */
class CustomReplacementTool extends CustomizableClass
{
    use FeedCustomReplacementReadRepositoryCreateTrait;

    protected ?int $feedId = null;

    public static function new(): static
    {
        return self::_new(self::class);
    }

    /**
     * Parse repetition with feed custom replacements
     */
    public function replace(string $content): string
    {
        $feedCustomReplacements = $this->createFeedCustomReplacementReadRepository()
            ->enableReadOnlyDb(true)
            ->filterFeedId($this->getFeedId())
            ->orderByOrder()
            ->loadEntities();
        foreach ($feedCustomReplacements as $feedCustomReplacement) {
            if ($feedCustomReplacement->Regexp) {
                $regexp = '/' . $feedCustomReplacement->Original . '/mu';
                $result = @preg_replace($regexp, $feedCustomReplacement->Replacement, $content);
                if ($result) {
                    $content = (string)$result;
                } else {
                    log_debug($this->getPregLastErrorText());
                }
            } else {
                $content = implode(
                    $feedCustomReplacement->Replacement,
                    mb_split(preg_quote($feedCustomReplacement->Original, '/'), $content)
                );
            }
        }
        return $content;
    }

    /**
     * Return message for preg_last_error()
     */
    protected function getPregLastErrorText(): string
    {
        $errorMessage = null;
        if (preg_last_error() === PREG_NO_ERROR) {
            $errorMessage = 'There is no error.';
        } elseif (preg_last_error() === PREG_INTERNAL_ERROR) {
            $errorMessage = 'There is an internal error!';
        } elseif (preg_last_error() === PREG_BACKTRACK_LIMIT_ERROR) {
            $errorMessage = 'Backtrack limit was exhausted!';
        } elseif (preg_last_error() === PREG_RECURSION_LIMIT_ERROR) {
            $errorMessage = 'Recursion limit was exhausted!';
        } elseif (preg_last_error() === PREG_BAD_UTF8_ERROR) {
            $errorMessage = 'Bad UTF8 error!';
        } elseif (preg_last_error() === PREG_BAD_UTF8_OFFSET_ERROR) {
            $errorMessage = 'Bad UTF8 offset error!';
        }
        return $errorMessage;
    }

    public function getFeedId(): ?int
    {
        return $this->feedId;
    }

    public function setFeedId(int $feedId): static
    {
        $this->feedId = $feedId;
        return $this;
    }
}
