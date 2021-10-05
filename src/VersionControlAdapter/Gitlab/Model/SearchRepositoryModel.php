<?php

declare(strict_types=1);

namespace Icanhazstring\RandomIssuePicker\VersionControlAdapter\Gitlab\Model;

use JMS\Serializer\Annotation as Serializer;

class SearchRepositoryModel
{
    /**
     * @var RepositoryModel[]
     * @Serializer\Inline
     * @Serializer\Type("array<Icanhazstring\RandomIssuePicker\VersionControlAdapter\Gitlab\Model\RepositoryModel>")
     */
    private $items;

    /**
     * @return RepositoryModel[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function findRandomWithOpenIssues(): ?RepositoryModel
    {
        foreach ($this->getItems() as $repository) {
            if ($repository->hasOpenIssues()) {
                return $repository;
            }
        }

        return null;
    }
}
