<?php

declare(strict_types=1);

namespace Icanhazstring\RandomIssuePicker\Model;

use JMS\Serializer\Annotation as Serializer;

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
     * @var string
     * @Serializer\Type("string")
     */
    private $state;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $created_at;

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

    public function getState(): string
    {
        return $this->state;
    }

    public function getCreatedDate(): string
    {
        return $this->created_at;
    }
}
