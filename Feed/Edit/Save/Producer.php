<?php
/**
 * SAM-4697: Feed entity editor
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 10, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Feed\Edit\Save;

use Feed;
use LogicException;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Feed\Edit\Internal\Load\DataProviderAwareTrait;
use Sam\Feed\Edit\Internal\Normalize\NormalizerAwareTrait;
use Sam\Feed\Edit\Internal\Normalize\NormalizerBase;
use Sam\Storage\WriteRepository\Entity\Feed\FeedWriteRepositoryAwareTrait;

/**
 * Class FeedEditor
 * @package Sam\Feed\Edit
 */
class Producer extends CustomizableClass
{
    use CurrentDateTrait;
    use DataProviderAwareTrait;
    use EditorUserAwareTrait;
    use EntityFactoryCreateTrait;
    use NormalizerAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use FeedWriteRepositoryAwareTrait;

    public const OK_CREATED = 1;
    public const OK_UPDATED = 2;

    /** @var Dto */
    protected Dto $dto;
    /**
     * Result Feed entity
     * @var Feed|null
     */
    protected ?Feed $feed = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Dto $dto
     * @param int $editorUserId
     * @param NormalizerBase $normalizer
     * @return static
     */
    public function construct(
        Dto $dto,
        int $editorUserId,
        NormalizerBase $normalizer
    ): Producer {
        $this->dto = $dto;
        $this->setEditorUserId($editorUserId);
        $this->setNormalizer($normalizer);

        $successMessages = [
            self::OK_CREATED => 'Feed created',
            self::OK_UPDATED => 'Feed updated',
        ];
        $this->getResultStatusCollector()->initAllSuccesses($successMessages);

        return $this;
    }

    /**
     * Create or update Feed entity in db
     * @return void
     */
    public function update(): void
    {
        $feed = $this->build();
        $this->getFeedWriteRepository()->saveWithModifier($feed, $this->getEditorUserId());
        $this->feed = $feed;
        $successCode = $this->dto->isNew() ? self::OK_CREATED : self::OK_UPDATED;
        $this->getResultStatusCollector()->addSuccess($successCode);
    }

    /**
     * @return Feed
     */
    protected function build(): Feed
    {
        $normalizer = $this->getNormalizer();
        $dto = $this->dto;
        $feed = $dto->isNew()
            ? $this->createEntityFactory()->feed()
            : $this->getDataProvider()->loadFeedById($normalizer->toInt($dto->feedId));
        if ($dto->isNew()) {
            $feed->AccountId = $normalizer->toInt($dto->accountId);
            $feed->Active = true;
        }
        if (isset($dto->currencyId)) {
            $feed->CurrencyId = $normalizer->toInt($dto->currencyId);
        }
        if (isset($dto->name)) {
            $feed->Name = $dto->name;
        }
        if (isset($dto->slug)) {
            $feed->Slug = $dto->slug;
        }
        if (isset($dto->feedType)) {
            $feed->FeedType = $dto->feedType;
        }
        if (isset($dto->cacheTimeout)) {
            $feed->CacheTimeout = $normalizer->toInt($dto->cacheTimeout);
        }
        if (isset($dto->itemsPerPage)) {
            $feed->ItemsPerPage = $normalizer->toInt($dto->itemsPerPage);
        }
        if (isset($dto->escaping)) {
            $feed->Escaping = $normalizer->toInt($dto->escaping);
        }
        if (isset($dto->encoding)) {
            $feed->Encoding = $dto->encoding;
        }
        if (isset($dto->locale)) {
            $feed->Locale = $dto->locale;
        }
        if (isset($dto->header)) {
            $feed->Header = $dto->header; // FYI: We do NOT trim this
        }
        if (isset($dto->repetition)) {
            $feed->Repetition = $dto->repetition; // FYI: We do NOT trim this
        }
        if (isset($dto->glue)) {
            $feed->Glue = $dto->glue; // FYI: We do NOT trim this
        }
        if (isset($dto->footer)) {
            $feed->Footer = $dto->footer; // FYI: We do NOT trim this
        }
        if (isset($dto->isIncludeInReports)) {
            $feed->IncludeInReports = $normalizer->toBool($dto->isIncludeInReports);
        }
        if (isset($dto->contentType)) {
            $feed->ContentType = $dto->contentType;
        }
        if (isset($dto->fileName)) {
            $feed->Filename = $normalizer->toFilename($dto->fileName);
        }
        if (isset($dto->isHideEmptyFields)) {
            $feed->HideEmptyFields = $normalizer->toBool($dto->isHideEmptyFields);
        }
        return $feed;
    }

    /**
     * @return string
     */
    public function successMessage(): ?string
    {
        return $this->getResultStatusCollector()->getConcatenatedSuccessMessage();
    }

    /**
     * @return Feed
     */
    public function getResultFeed(): Feed
    {
        if (!$this->feed) {
            throw new LogicException('You should call update() first to load Feed');
        }
        return $this->feed;
    }
}
