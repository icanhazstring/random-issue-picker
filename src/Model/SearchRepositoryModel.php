<?php

declare(strict_types=1);

namespace Icanhazstring\RandomIssuePicker\Model;

use JMS\Serializer\Annotation as Serializer;

class SearchRepositoryModel
{
    /**
     * @var int
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("total_count")
     */
    private $totalCount;

    /**
     * @var RepositoryModel[]
     * @Serializer\Type("array<Icanhazstring\RandomIssuePicker\Model\RepositoryModel>")
     */
    private $items;

    /**
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    /**
     * @return RepositoryModel[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function findFirstWithOpenIssues(): ?RepositoryModel
    {
        foreach ($this->getItems() as $repository) {
            if ($repository->hasOpenIssues()) {
                return $repository;
            }
        }

        return null;
    }
}