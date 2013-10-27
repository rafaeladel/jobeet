<?php
namespace Ibw\UserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ibw\UserBundle\Entity\User;

class IbwUsersCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName("ibw:users:add")
            ->setDescription("Add Ibw users")
            ->addArgument("username", InputArgument::REQUIRED, "The username")
            ->addArgument("password", InputArgument::REQUIRED, "The password")
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');

        $em = $this->getContainer()->get('doctrine')->getManager();

        $user = new User($this->getContainer());
        $user->setUsername($username);
        $user->setPassword($password);
        $em->persist($user);
        $em->flush();

        $output->writeln(sprintf("User: %s is saved with password\n%s", $user->getUsername(), $user->getPassword()));

    }
}

?>