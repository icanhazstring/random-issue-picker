<?php

declare(strict_types=1);

namespace Icanhazstring\RandomIssuePicker\VersionControlAdapter\Gitlab\Request;

use Icanhazstring\RandomIssuePicker\VersionControlAdapter\RequestInterface;

class RepositorySearchRequest implements RequestInterface
{
    /** @var int */
    private $page;

    /** @var int */
    private $resultsPerPage;

    /** @var string */
    private $language;

    /** @var string[] */
    private $topics;

    /**
     * @param string[] $topics
     */
    public function __construct(int $page = 1, int $resultsPerPage = 100, string $language = 'php', array $topics = [])
    {
        $this->page = $page;
        $this->resultsPerPage = $resultsPerPage;
        $this->language = $language;
        $this->topics = $topics;
    }

    public function getMethod(): string
    {
        return RequestInterface::METHOD_GET;
    }

    public function getUrl(): string
    {
        return 'https://gitlab.com/api/v4/projects';
    }

    /**
     * @return array<string, int|string|bool>
     */
    public function getQueryParameters(): array
    {
        return [
            'topic' => implode(',', $this->topics),
            'with_programming_language' => $this->language,
            'per_page' => $this->resultsPerPage,
            'page' => $this->page,
            'visibility' => 'public',
            'with_merge_requests_enabled' => true,
            'order_by' => 'last_activity_at'
        ];
    }
}
