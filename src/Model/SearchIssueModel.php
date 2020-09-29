<?php

declare(strict_types=1);

namespace Icanhazstring\RandomIssuePicker\Model;

use JMS\Serializer\Annotation as Serializer;

class SearchIssueModel
{
    /**
     * @var IssueModel[]
     * @Serializer\Type("array<Icanhazstring\RandomIssuePicker\Model\IssueModel>")
     */
    private $items;

    /**
     * @return IssueModel[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
