<?php

declare(strict_types=1);

namespace Icanhazstring\RandomIssuePicker\Model;

use JMS\Serializer\Annotation as Serializer;
use DateTimeImmutable;

class IssueModel
{
    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\SerializedName("html_url")
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
     */
    private $body;

    /**
     * @var DateTimeImmutable
     * @Serializer\Type("DateTimeImmutable")
     * @Serializer\SerializedName("created_at")
     */
    private $createdAt;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $state;

    /**
     * @var array<int,array<string,mixed>>
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
        return array_map(function ($x): string {
            return $x["name"];
        }, $this->labels);
    }
}
