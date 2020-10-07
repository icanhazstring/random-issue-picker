<?php

declare(strict_types=1);

namespace Icanhazstring\RandomIssuePicker\Writer;

use Icanhazstring\RandomIssuePicker\Model\IssueModel;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class IssueWriter
{
    /** @var OutputInterface */
    private $output;

    /** @var SymfonyStyle */
    private $io;

    /** @var IssueModel */
    private $issueModel;

    public function __construct(OutputInterface $output, SymfonyStyle $io, IssueModel $issueModel)
    {
        $this->output = $output;
        $this->io = $io;
        $this->issueModel = $issueModel;
    }

    public function write(): void
    {

        $randomIssueTitle = $this->issueModel->getTitle();

        // cut off title at 70 characters
        if (strlen($this->issueModel->getTitle()) > 70) {
            $randomIssueTitle = substr($this->issueModel->getTitle(), 0, 70) . '...';
        }

        $randomIssueBody = $this->issueModel->getBody();

        // cut off body at 300 characters
        if (strlen($this->issueModel->getBody()) > 300) {
            $randomIssueBody = substr($this->issueModel->getBody(), 0, 300) . '...';
        }

        // output details heading
        $this->io->writeln([
            "",
            "<comment> Details:</>",
            sprintf("<comment> %s</>", str_repeat("-", 70))
        ]);

        // create a table for displaying the title and link
        $table = new Table($this->output);
        $table->setHeaders([
            // label in white text, title in green text
            ['<fg=white>Title:</>', sprintf('<info>%s</>', $randomIssueTitle)]
        ]);
        $table->setRows([
            ['Link:', sprintf("<href=%s>%s</>", $this->issueModel->getUrl(), $this->issueModel->getUrl())],
            ['Date Created:', $this->issueModel->getCreatedAt()->format('D, j M Y g:i A \U\T\C')],
            ['Status:', $this->issueModel->getState()],
            ['Labels:', implode(", ", $this->issueModel->getLabels())],
        ]);
        $table->setStyle('borderless');
        $table->setColumnMaxWidth(1, 80);
        // render table
        $table->render();

        // print body only if it is not empty
        if (strlen($this->issueModel->getBody()) > 0) {
            // output issue heading
            $this->io->writeln([
                "",
                "<comment> Issue:</>",
                sprintf("<comment> %s</>", str_repeat("-", 70))
            ]);
            // wrap lines that are longer than 70 characters
            $randomIssueBodyLines = wordwrap($randomIssueBody, 70, "\n", true);
            // indent all lines with two spaces
            $randomIssueBodyLines = preg_replace("/(^|\n)/", "$1  ", $randomIssueBodyLines);
            // render body
            $this->io->writeln(strval($randomIssueBodyLines));
        }

        $this->io->newLine(1);
    }
}
