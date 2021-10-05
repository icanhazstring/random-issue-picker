<?php

declare(strict_types=1);

namespace Icanhazstring\RandomIssuePicker\VersionControlAdapter\Gitlab\Model;

use JMS\Serializer\Annotation as Serializer;

class SearchIssueModel
{
    /**
     * @var IssueModel[]
     * @Serializer\Inline()
     * @Serializer\Type("array<Icanhazstring\RandomIssuePicker\VersionControlAdapter\Gitlab\Model\IssueModel>")
     */
    private $items;

    /**
     * @return IssueModel[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getRandom(): ?IssueModel
    {
        $items = $this->getItems();
        if (count($items) === 0) {
            return null;
        }

        shuffle($items);

        return array_shift($items);
    }
}
