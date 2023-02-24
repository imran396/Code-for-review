<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SearchIndexFulltext;

class SearchIndexFulltextDeleteRepository extends AbstractSearchIndexFulltextDeleteRepository
{
    /** @var string[] */
    protected array $joins = [
        'invoice' => 'JOIN invoice i ON i.id = sif.entity_id',
    ];

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Join with invoice
     * @return static
     */
    public function innerJoinInvoice(): static
    {
        $this->innerJoin('invoice');
        return $this;
    }
}
