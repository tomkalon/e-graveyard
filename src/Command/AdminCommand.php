<?php

namespace App\Command;

use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'Admin',
    description: 'Add a short description for your command',
)]
class AdminCommand extends Command
{
    public function __construct(private readonly UserRepository $userRepository)
    {
        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            ->setHelp('Polecenie pomoże Ci przypisać określoną rolę do konkretnego użytkownika.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        // role name
        $getRole = new Question(
            'Jaką ROLĘ chcesz przypisać do konta? Podaj samą nazwę (np. ADMIN, MANAGER): ',
            'ROLE_NAME'
        );
        $roleName = 'ROLE_' . $helper->ask($input, $output, $getRole);

        // get list of users email
        $userList = $this->userRepository->findAllAttr('email');
        $arr = array();
        foreach ($userList as $key => $element) {
            $arr[$key] = $element['email'];
        }
        // adding ROLE
        $addChoice = new ConfirmationQuestion(
            'Czy chcesz przypisać do konta rolę ' . $roleName . '? (y/n)',
            false,
            '/^(y)/i'
        );

        if ($helper->ask($input, $output, $addChoice)) {
            $question = new ChoiceQuestion(
                'Wskaż email konta, do którego przypisać uprawnienia:' . $roleName . '!',
                $arr,
                '...'
            );

            $selected_user = $helper->ask($input, $output, $question);
            $user = $this->userRepository->findOneBy([
                'email' => $selected_user
            ]);

            $user->setRoles([$roleName]);
            $this->userRepository->save($user, true);
            $io = new SymfonyStyle($input, $output);
            $io->success(sprintf('Rola została dodana do konta o adresie email: %s', $selected_user));
        } else {
            $io = new SymfonyStyle($input, $output);
            $io->success('Brak akcji do wykonania');
        }
        // removing ROLE
        $removeChoice = new ConfirmationQuestion(
            'Czy chcesz usunąć z konta wszystkie uprawnienia? (y/n)',
            false,
            '/^(y)/i'
        );

        if ($helper->ask($input, $output, $removeChoice)) {
            $question = new ChoiceQuestion(
                'Wskaż email konta, z którego usunąć wszystkie uprawnienia!',
                $arr,
                '...'
            );

            $selected_user = $helper->ask($input, $output, $question);
            $user = $this->userRepository->findOneBy([
                'email' => $selected_user
            ]);

            $user->setRoles(['']);
            $this->userRepository->save($user, true);
            $io = new SymfonyStyle($input, $output);
            $io->success(sprintf('Rola administratora została usunięta z konta o adresie email: %s', $selected_user));
        }
        return Command::SUCCESS;
    }
}
