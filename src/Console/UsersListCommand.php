<?php


namespace Oxygen\Core\Console;


use Oxygen\Auth\Entity\User;
use Oxygen\Auth\Repository\UserRepositoryInterface;
use Symfony\Component\Console\Helper\Table;

class UsersListCommand extends Command {

    /**
     * @var string name and signature of console command
     */
    protected $signature = 'users:list ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send drip e-mails to a user';

    private $headers = [
        'Id',
        'Name',
        'Full Name',
        'Email',
        'Group',
        'Created At',
        'Updated At'
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display users in a table.
     * @param UserRepositoryInterface $users
     */
    public function handle(UserRepositoryInterface $users)
    {
        $users = $users->all();
        $usersRows = array_map(function(User $user) {
            return [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'fullName' => $user->getFullName(),
                'email' => $user->getEmail(),
//                'preferences' => json_encode($user->getPreferences()->toArray()),
//                'permissions' => json_encode($user->getGroup()->getPermissions()),
                'group' => json_encode($user->getGroup()->toArray()),
                'createdAt' => $user->getCreatedAt() !== null ? $user->getCreatedAt()->format(\DateTime::ATOM) : null,
                'updatedAt' => $user->getUpdatedAt() !== null ? $user->getUpdatedAt()->format(\DateTime::ATOM) : null
            ];
        }, $users);

        $generalTable = new Table($this->output);
        $generalTable->setHeaders($this->headers);
        $generalTable->setRows($usersRows);
        $generalTable->render();
    }

}
