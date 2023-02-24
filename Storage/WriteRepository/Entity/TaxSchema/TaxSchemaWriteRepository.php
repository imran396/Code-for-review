<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\TaxSchema;

use QMySqliDatabaseException;
use Sam\Tax\StackedTax\Schema\Exception\DuplicateTaxSchemaNameException;
use TaxSchema;

class TaxSchemaWriteRepository extends AbstractTaxSchemaWriteRepository
{
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @inheritDoc
     */
    public function save(TaxSchema $entity): void
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
    public function saveWithModifier(TaxSchema $entity, int $editorUserId): void
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
    public function saveWithSystemModifier(TaxSchema $entity): void
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
    public function forceSave(TaxSchema $entity): void
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
    public function forceSaveWithModifier(TaxSchema $entity, int $editorUserId): void
    {
        try {
            parent::forceSaveWithModifier($entity, $editorUserId);
        } catch (QMySqliDatabaseException $exception) {
            $this->handleException($exception);
        }
    }

    protected function handleException(QMySqliDatabaseException $exception): void
    {
        if (DuplicateTaxSchemaNameException::isDuplicateTaxSchemaNameError($exception->getMessage())) {
            throw DuplicateTaxSchemaNameException::fromQMySqliDatabaseException($exception);
        }
        throw $exception;
    }
}
