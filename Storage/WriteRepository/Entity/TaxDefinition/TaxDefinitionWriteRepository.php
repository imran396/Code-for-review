<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\TaxDefinition;

use QMySqliDatabaseException;
use Sam\Tax\StackedTax\Definition\Exception\DuplicateTaxDefinitionNameException;
use TaxDefinition;

class TaxDefinitionWriteRepository extends AbstractTaxDefinitionWriteRepository
{
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @inheritDoc
     */
    public function save(TaxDefinition $entity): void
    {
        try {
            parent::save($entity);
        } catch (QMySqliDatabaseException $exception) {
            $this->handleException($exception);
        }
    }

    /**
     * @inheritDoc
     */
    public function saveWithModifier(TaxDefinition $entity, int $editorUserId): void
    {
        try {
            parent::saveWithModifier($entity, $editorUserId);
        } catch (QMySqliDatabaseException $exception) {
            $this->handleException($exception);
        }
    }

    /**
     * @inheritDoc
     */
    public function saveWithSystemModifier(TaxDefinition $entity): void
    {
        try {
            parent::saveWithSystemModifier($entity);
        } catch (QMySqliDatabaseException $exception) {
            $this->handleException($exception);
        }
    }

    /**
     * @inheritDoc
     */
    public function forceSave(TaxDefinition $entity): void
    {
        try {
            parent::forceSave($entity);
        } catch (QMySqliDatabaseException $exception) {
            $this->handleException($exception);
        }
    }

    /**
     * @inheritDoc
     */
    public function forceSaveWithModifier(TaxDefinition $entity, int $editorUserId): void
    {
        try {
            parent::forceSaveWithModifier($entity, $editorUserId);
        } catch (QMySqliDatabaseException $exception) {
            $this->handleException($exception);
        }
    }

    protected function handleException(QMySqliDatabaseException $exception): void
    {
        if (DuplicateTaxDefinitionNameException::isDuplicateTaxDefinitionNameError($exception->getMessage())) {
            throw DuplicateTaxDefinitionNameException::fromQMySqliDatabaseException($exception);
        }
        throw $exception;
    }
}
