<?php

declare(strict_types=1);

namespace Icanhazstring\RandomIssuePicker\Command;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Icanhazstring\RandomIssuePicker\Model\SearchIssueModel;
use Icanhazstring\RandomIssuePicker\Model\SearchRepositoryModel;
use Icanhazstring\RandomIssuePicker\Request\IssueSearchRequest;
use Icanhazstring\RandomIssuePicker\Request\RepositorySearchRequest;
use Icanhazstring\RandomIssuePicker\Writer\IssueWriter;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RandomIssueCommand extends Command
{
    /** @var Client */
    private $client;

    /** @var SymfonyStyle */
    private $io;

    public function __construct()
    {
        parent::__construct('random:issue');

        $this->client = new Client();
    }

    protected function configure(): void
    {
        $this->setDescription('Select a random issue and show link and description.');

        $this->addOption(
            'topic',
            't',
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            'Which topic(s) should the repository have?',
            []
        );

        $this->addOption(
            'language',
            'l',
            InputOption::VALUE_OPTIONAL,
            'Which language do you look for e.g. php, js, java, default is php'
        );

        $this->addOption(
            'label',
            '',
            InputOption::VALUE_OPTIONAL,
            'Do you want issues for #hacktoberfest?'
        );
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);

        /** @var string|null $languageInput */
        $languageInput = $input->getOption('language');
        /** @var string|null $labelInput */
        $labelInput = $input->getOption('label');
        /** @var string[]|null topicsInput */
        $topicsInput = $input->getOption('topic');

        $language = $languageInput ?? 'php';
        $label = $labelInput ?? '';
        $topics = !empty($topicsInput) ? $topicsInput : ['hacktoberfest'];

        $randomRepositoryName = $this->findRandomRepository($language, $topics);
        if ($randomRepositoryName === null) {
            $this->io->warning(
                sprintf(
                    'No repositories found with language "%s" and topics "%s"',
                    $language,
                    implode(',', $topics)
                )
            );

            return 0;
        }

        $randomIssue = $this->findIssues($randomRepositoryName)->getRandom();
        if (!$randomIssue) {
            $this->io->warning("No issue was found with your language: $language and label: $label");

            return 0;
        }

        (new IssueWriter($output, $this->io, $randomIssue))->write();

        return 0;
    }

    private function findIssues(string $repository): SearchIssueModel
    {
        $issueSearchRequest = new IssueSearchRequest(
            $this->getRandomPageIndex(1),
            100,
            $repository
        );

        $rawResponse = $this->client->request(
            $issueSearchRequest->getMethod(),
            $issueSearchRequest->getUrl(),
            $issueSearchRequest->getQueryParameters()
        );

        $serializer = SerializerBuilder::create()->build();

        return $serializer->deserialize(
            (string) $rawResponse->getBody(),
            SearchIssueModel::class,
            'json'
        );
    }

    /**
     * @param string[] $topics
     */
    private function findRandomRepository(string $language, array $topics): ?string
    {
        if (empty($topics)) {
            return null;
        }

        $repositorySearchRequest = new RepositorySearchRequest(
            $this->getRandomPageIndex(),
            100,
            $language,
            $topics
        );

        $rawResponse = $this->client->request(
            $repositorySearchRequest->getMethod(),
            $repositorySearchRequest->getUrl(),
            $repositorySearchRequest->getQueryParameters()
        );

        $serializer = SerializerBuilder::create()->build();

        /** @var SearchRepositoryModel $searchRepositoryModel */
        $searchRepositoryModel = $serializer->deserialize(
            (string) $rawResponse->getBody(),
            SearchRepositoryModel::class,
            'json'
        );

        $randomRepository = $searchRepositoryModel->findFirstWithOpenIssues();

        return $randomRepository ? $randomRepository->getFullName() : null;
    }

    private function getRandomPageIndex(int $max = 10): int
    {
        return random_int(1, $max);
    }
}
