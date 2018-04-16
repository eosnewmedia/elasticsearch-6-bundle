<?php
declare(strict_types=1);

namespace Enm\Bundle\Elasticsearch\Command;

use Enm\Elasticsearch\DocumentManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class IndexCreateCommand extends AbstractIndexCommand
{
    /**
     * @param DocumentManagerInterface $elasticsearch
     * @param string[] $types
     * @throws \Exception
     */
    public function __construct(DocumentManagerInterface $elasticsearch, array $types)
    {
        parent::__construct('create', $elasticsearch, $types);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $types = $this->getTypes($input);

        if (\count($types) === 0) {
            $this->documentManager()->createIndex();
        } else {
            foreach ($types as $type) {
                $this->documentManager()->createIndex($type);
            }
        }

        (new SymfonyStyle($input, $output))->success('Indices created.');
    }
}
