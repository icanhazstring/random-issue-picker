<?php

declare(strict_types=1);

namespace Icanhazstring\RandomIssuePicker\VersionControlAdapter\Gitlab\Model;

use Icanhazstring\RandomIssuePicker\VersionControlAdapter\RepositoryModelInterface;
use JMS\Serializer\Annotation as Serializer;

class RepositoryModel implements RepositoryModelInterface
{
    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("id")
     */
    private $id;

    /**
     * @var int
     * @Serializer\Type("int")
     * @Serializer\SerializedName("open_issues_count")
     */
    private $openIssuesCount;

    public function getIdentifier(): string
    {
        return $this->id;
    }

    public function hasOpenIssues(): bool
    {
        return $this->openIssuesCount > 0;
    }
}
