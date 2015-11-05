<?php
/**
 * Created by PhpStorm.
 * User: zxm
 * Date: 11/4/15
 * Time: 2:07 PM
 */

namespace Clout\Commands;
use Clout\Users\UserService;

class ExitCommand extends UserServiceCommand
{
    const NAME = 'exit';
    const PATTERN = '/^exit$/i';

    /**
     * @param $input
     */
    public function parse($input) {
        $this->arguments[] = $input =='exit';
    }

    /**
     * Exit the CLI
     * @param UserService $userService
     */
    public function execute(UserService $userService) {
        if ($this->arguments[0] == 1) {
            echo "clout exited!\n";
            exit;
        } else {
            echo "Type help to see the tips of commands \n";
        }
    }
}