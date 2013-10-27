<?php
namespace Ibw\UserBundle\Command;

use Ibw\UserBundle\Entity\Role;
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
            ->addArgument("roles", InputArgument::IS_ARRAY, "User role", array('ROLE_USER'))
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');
        $roles = $input->getArgument('roles');

        $em = $this->getContainer()->get('doctrine')->getManager();

        $user = new User($this->getContainer());
        $user->setUsername($username);
        $user->setPassword($password);
        foreach($roles as $key => $role_name)
        {
            if(!preg_match('/^ROLE_/', $role_name))
            {
                $role_name = "ROLE_". $role_name;
            }
            $role_name = strtoupper($role_name);

            $role = $em->getRepository('IbwUserBundle:Role')->getRoleIfExists($role_name);
            if($role === null)
            {
                $role = new Role();
                //Convert from "ROLE_SUPER_ADMIN" to "Super admin".
                $name = ucfirst(str_replace("_", " ", strtolower(substr($role_name, 5))));
                $role->setName($name);
                $role->setRole($role_name);
            }

            $user->addRole($role);

            //Updating roles array element in case if it's updated
            $roles[$key] = $role_name;
        }

        $em->persist($user);
        $em->flush();

        $output->writeln(sprintf("\nUser is saved.\nUsername: %s\nPassword: %s\nRoles: %s", $user->getUsername(), $user->getPassword(), implode(", ", $roles)));

    }
}

?>