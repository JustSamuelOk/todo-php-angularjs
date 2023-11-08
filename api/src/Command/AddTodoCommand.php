<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Factory\TodoFactory;

#[AsCommand(
    name: 'app:add-todo',
    description: 'Add a short description for your command',
)]
class AddTodoCommand extends Command
{
    public function __construct(private EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('title', InputArgument::REQUIRED, 'Title of todo')
            ->addArgument('description', InputArgument::REQUIRED, 'Description of todo')
            ->addArgument('completed', InputArgument::OPTIONAL, 'Completed of todo');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $title = $input->getArgument('title');

        if ($title) {
            $io->note(sprintf('Title: %s', $title));
        }

        $description = $input->getArgument('description');

        if ($description) {
            $io->note(sprintf('Description: %s', $description));
        }

        $entity = TodoFactory::create($title, $description);

        $completed = $input->getArgument('completed');

        if ($completed) {
            $entity->setCompleted(true);
        }

        $this->em->persist($entity);
        $this->em->flush();

        //        if ($input->getOption('option1')) {
        //            // ...
        //        }

        $io->success('Todo is saved: ' . $entity->getId());

        return Command::SUCCESS;
    }
}
