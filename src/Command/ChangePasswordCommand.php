<?php

namespace App\Command;

use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'ChangePassword',
    description: 'Password changer',
)]
class ChangePasswordCommand extends Command
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
        private readonly UserRepository $userRepository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('Zmiana hasła użytkownika.');
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $io = new SymfonyStyle($input, $output);

        // get username
        $getUsername = new Question(
            'Nazwa użytkownika:',
            false,
        );
        $username = $helper->ask($input, $output, $getUsername);
        if (!$username || !preg_match("/^[a-zA-Z-' ]*$/", $username)) {
            $io->error('Akcja anulowana! Błędna nazwa użytkownika.');
            return Command::FAILURE;
        }
        $result = $this->userRepository->findAllObjectAttrValue('username', $username);
        if ($result) {
            $user = $result[0];
            // set password
            $newPassword = new Question(
                'Hasło (co najmniej 6 znaków):',
                false,
            );
            $password = $helper->ask($input, $output, $newPassword);
            if (strlen($password) < 6) {
                $io->error('Hasło jest zbyt krótkie.');
                return Command::FAILURE;
            }
            // set password repeat
            $newPasswordRepeat = new Question(
                'Powtórz hasło:',
                false,
            );
            $passwordRepeat = $helper->ask($input, $output, $newPasswordRepeat);
            if (strlen($passwordRepeat) < 6) {
                $io->error('Wpisane hasła różnią się od siebie.');
                return Command::FAILURE;
            }
            if ($password === $passwordRepeat) {
                $user->setPassword($this->hasher->hashPassword($user, $password));
                $this->userRepository->save($user, true);
                $io->success(sprintf('Pomyślnie zmieniono hasło użytkownika "%s".', $username));
                return Command::SUCCESS;
            } else {
                $io->error('Wpisane hasła różnią się od siebie.');
                return Command::FAILURE;
            }
        } else {
            $io->error('Brak użytkownika o podanej nazwie.');
            return Command::FAILURE;
        }
    }
}
