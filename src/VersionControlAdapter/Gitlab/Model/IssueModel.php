<?php

declare(strict_types=1);

namespace Icanhazstring\RandomIssuePicker\VersionControlAdapter\Gitlab\Model;

use Icanhazstring\RandomIssuePicker\VersionControlAdapter\IssueModelInterface;
use JMS\Serializer\Annotation as Serializer;
use DateTimeImmutable;

class IssueModel implements IssueModelInterface
{
    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("web_url")
     */
    private $url;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $title;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("description")
     */
    private $body;

    /**
     * @var DateTimeImmutable
     * @Serializer\Type("DateTimeImmutable<'Y-m-d*H:i:s.u*'>")
     * @Serializer\SerializedName("created_at")
     */
    private $createdAt;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $state;

    /**
     * @var array<int,string>
     * @Serializer\Type("array")
     */
    private $labels;


    public function getUrl(): string
    {
        return $this->url;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @return array<string>
     */
    public function getLabels(): array
    {
        return $this->labels;
    }
}
