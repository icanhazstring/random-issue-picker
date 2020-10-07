<?php

declare(strict_types=1);

namespace Icanhazstring\RandomIssuePicker\Model;

use JMS\Serializer\Annotation as Serializer;

class RepositoryModel
{
    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("full_name")
     */
    private $fullName;

    /**
     * @var int
     * @Serializer\Type("int")
     * @Serializer\SerializedName("open_issues_count")
     */
    private $openIssuesCount;

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function hasOpenIssues(): bool
    {
        return $this->openIssuesCount > 0;
    }
}
