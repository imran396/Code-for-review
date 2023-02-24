<?php
/**
 * Abstract Base class for feed output builders, independent of processing entity (lot feed, auction feed)
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         May 28, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Core\Feed;

use Feed;
use InvalidArgumentException;
use Sam\Core\Constants;
use Sam\Core\Format\Rtf\RtfEncoder;
use Sam\Details\Core\Render\EscapingTool;
use Sam\Feed\Load\FeedLoaderAwareTrait;

/**
 * Class Builder
 * @package Sam\Details
 * @property EscapingTool $escapingTool
 */
abstract class Builder extends \Sam\Details\Core\Builder
{
    use FeedLoaderAwareTrait;

    protected string $body = '';
    protected ?CacheManager $cacheManager = null;
    protected ?CustomReplacementTool $customReplacementTool = null;
    protected ?Encoder $encoder = null;
    protected ?Feed $feed = null;
    protected ?string $feedSlug = null;
    protected ?RtfEncoder $rtfManager = null;

    public function render(): void
    {
        $this->sendHttpHeader();
        if (!$this->getCacheManager()->sendCached()) {
            $output = $this->renderHeader()
                . $this->renderBody()
                . $this->renderFooter();
            $this->cacheManager->saveCache($output);
            echo $output;
        }
    }

    /**
     * Send raw HTTP headers
     */
    protected function sendHttpHeader(): void
    {
        $feed = $this->getFeed();
        if ($feed->ContentType) {
            header("Content-Type: " . $feed->ContentType);
        }
        if ($feed->Filename) {
            header(
                'Content-Disposition: attachment; '
                . 'filename="' . $feed->Filename . '"'
            );
        }
    }

    protected function renderHeader(): string
    {
        $output = $this->getFeed()->Header;
        $ts = microtime(true);
        if ($this->isProfiling()) {
            log_debug(composeLogData(['render header' => ((microtime(true) - $ts) * 1000) . 'ms']));
        }
        return $output;
    }

    protected function renderBody(): string
    {
        $startTs = microtime(true);

        $feed = $this->getFeed();
        $this->body = $this->composeBody();
        $this->body = $this->getCustomReplacementTool()->replace($this->body);
        $this->body = $this->getEncoder()->encode($this->body);
        if ($feed->Escaping === Constants\Feed::ESC_RTF) {
            $this->body = $this->getRtfManager()->encode($this->body, $feed->Encoding);
        }

        if ($this->isProfiling()) {
            $spentMs = (microtime(true) - $startTs) * 1000;
            log_debug("Body rendered per {$spentMs}ms");
        }

        return $this->body;
    }

    /**
     * Compose body content based on requested data and feed template.
     */
    protected function composeBody(): string
    {
        $placeholderManager = $this->getPlaceholderManager();
        $placeholders = $placeholderManager->getActualPlaceholders();
        $templateParser = $this->getTemplateParser();
        $feed = $this->getFeed();
        $template = $feed->Repetition;
        $glue = '';
        foreach ($this->loadData() as $i => $row) {
            $ts = microtime(true);
            $this->body .= $glue . $templateParser->parse($template, $placeholders, $row);
            $glue = $feed->Glue;

            if ($this->isProfiling()) {
                $spentMs = (microtime(true) - $ts) * 1000;
                $memory = memory_get_usage(true);
                log_trace(
                    composeLogData(
                        [
                            "Iteration#" => $i,
                            "renderer per" => $spentMs . 'ms',
                            "memory usage" => $memory,
                        ]
                    )
                );
            }
        }
        return $this->body;
    }

    protected function loadData(): array
    {
        $startTs = microtime(true);

        $rows = $this->getDataProvider()->load();

        if ($this->isProfiling()) {
            $spentMs = (microtime(true) - $startTs) * 1000;
            log_trace(composeSuffix(["Lot feed query" => $spentMs . "ms"]));
        }
        return $rows;
    }

    protected function renderFooter(): string
    {
        $output = $this->getFeed()->Footer;
        $ts = microtime(true);
        if ($this->isProfiling()) {
            log_debug(composeLogData(['render footer' => ((microtime(true) - $ts) * 1000) . 'ms']));
        }
        return $output;
    }

    public function getFeedSlug(): string
    {
        if ($this->feedSlug === null) {
            throw new InvalidArgumentException('Feed slug not defined');
        }
        return $this->feedSlug;
    }

    public function setFeedSlug(string $feedSlug): static
    {
        $this->feedSlug = trim($feedSlug);
        return $this;
    }

    public function getFeed(): Feed
    {
        if ($this->feed === null) {
            $slug = $this->getFeedSlug();
            $systemAccountId = $this->getSystemAccountId();
            $this->feed = $this->getFeedLoader()->loadBySlug($slug, $systemAccountId, true);
            if ($this->feed === null) {
                throw new InvalidArgumentException("Feed not found by slug: {$slug}");
            }
        }
        return $this->feed;
    }

    public function setFeed(Feed $feed): static
    {
        $this->feed = $feed;
        return $this;
    }

    public function getTemplate(): string
    {
        if ($this->template === null) {
            $this->template = $this->getFeed()->Repetition;
        }
        return $this->template;
    }

    public function getCustomReplacementTool(): CustomReplacementTool
    {
        if ($this->customReplacementTool === null) {
            $this->customReplacementTool = CustomReplacementTool::new()
                ->setFeedId($this->getFeed()->Id);
        }
        return $this->customReplacementTool;
    }

    /**
     * @internal
     */
    public function setCustomReplacementTool(CustomReplacementTool $customReplacementTool): static
    {
        $this->customReplacementTool = $customReplacementTool;
        return $this;
    }

    public function getEncoder(): Encoder
    {
        if ($this->encoder === null) {
            $this->encoder = Encoder::new()
                ->setEncoding($this->getFeed()->Encoding);
        }
        return $this->encoder;
    }

    /**
     * @internal
     */
    public function setEncoder(Encoder $encoder): static
    {
        $this->encoder = $encoder;
        return $this;
    }

    public function getCacheManager(): CacheManager
    {
        if ($this->cacheManager === null) {
            $this->cacheManager = CacheManager::new()
                ->setCacheKey($this->fingerprint())
                ->setCacheTimeout((int)$this->getFeed()->CacheTimeout)
                ->enableProfiling($this->isProfiling());
        }
        return $this->cacheManager;
    }

    /**
     * @internal
     */
    public function setCacheManager(CacheManager $cacheManager): static
    {
        $this->cacheManager = $cacheManager;
        return $this;
    }

    public function getEscapingTool(): EscapingTool
    {
        if ($this->escapingTool === null) {
            $this->escapingTool = EscapingTool::new()
                ->setEscapingType($this->getFeed()->Escaping)
                ->setEncoding($this->getFeed()->Encoding)
                ->enableProfiling($this->isProfiling());
        }
        return $this->escapingTool;
    }

    public function getRtfManager(): RtfEncoder
    {
        if ($this->rtfManager === null) {
            $this->rtfManager = RtfEncoder::new();
        }
        return $this->rtfManager;
    }

    /**
     * @internal
     */
    public function setRtfManager(RtfEncoder $rtfManager): static
    {
        $this->rtfManager = $rtfManager;
        return $this;
    }

    protected function fingerprint(): string
    {
        return $this->options->fingerprint() . $this->feed->Id;
    }
}
