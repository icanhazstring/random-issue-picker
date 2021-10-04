<?php

namespace Icanhazstring\RandomIssuePicker\VersionControlAdapter;

use GuzzleHttp\Client;
use Icanhazstring\RandomIssuePicker\VersionControlAdapter\Gitlab\Model\IssueModel;
use Icanhazstring\RandomIssuePicker\VersionControlAdapter\Gitlab\Model\RepositoryModel;
use Icanhazstring\RandomIssuePicker\VersionControlAdapter\Gitlab\Model\SearchIssueModel;
use Icanhazstring\RandomIssuePicker\VersionControlAdapter\Gitlab\Model\SearchRepositoryModel;
use Icanhazstring\RandomIssuePicker\VersionControlAdapter\Gitlab\Request\IssueSearchRequest;
use Icanhazstring\RandomIssuePicker\VersionControlAdapter\Gitlab\Request\RepositorySearchRequest;
use JMS\Serializer\SerializerBuilder;

class Gitlab implements VersionControlAdapterInterface
{
    /** @var Client */
    private $client;
    /** @var string|null */
    private $personalAccessToken;

    public function __construct(Client $client, ?string $personalAccessToken)
    {
        if ($personalAccessToken === null) {
            throw new \Exception('A personal access token for this adapter is required');
        }
        $this->client = $client;
        $this->personalAccessToken = $personalAccessToken;
    }

    /**
     * @param string[] $topics
     */
    public function findRandomRepository(string $language, array $topics): ?RepositoryModelInterface
    {
        $repositorySearchRequest = new RepositorySearchRequest(
            $this->getRandomPageIndex(1),
            20,
            $language,
            $topics
        );

        $rawResponse = $this->client->request(
            $repositorySearchRequest->getMethod(),
            $repositorySearchRequest->getUrl() . '?' . http_build_query($repositorySearchRequest->getQueryParameters()),
            [
                'headers' => [
                    'PRIVATE-TOKEN' => $this->personalAccessToken
                ]
            ]
        );

        $serializer = SerializerBuilder::create()->build();

        /** @var SearchRepositoryModel $searchRepositoryModel */
        $searchRepositoryModel = $serializer->deserialize(
            (string)$rawResponse->getBody(),
            SearchRepositoryModel::class,
            'json'
        );

        return $searchRepositoryModel->findRandomWithOpenIssues();
    }

    public function findRandomIssueFromRepository(RepositoryModelInterface $repository, string $label): ?IssueModelInterface
    {
        $issueSearchRequest = new IssueSearchRequest(
            $this->getRandomPageIndex(1),
            100,
            $label,
            $repository->getIdentifier()
        );

        $rawResponse = $this->client->request(
            $issueSearchRequest->getMethod(),
            $issueSearchRequest->getUrl() . '?' . http_build_query($issueSearchRequest->getQueryParameters()),
            [
                'headers' => [
                    'PRIVATE-TOKEN' => $this->personalAccessToken
                ]
            ]
        );

        $serializer = SerializerBuilder::create()->build();

        /** @var SearchIssueModel $issueModel */
        $issueModel = $serializer->deserialize(
            (string) $rawResponse->getBody(),
            SearchIssueModel::class,
            'json'
        );

        return $issueModel->getRandom();
    }

    private function getRandomPageIndex(int $max = 10): int
    {
        return random_int(1, $max);
    }
}
