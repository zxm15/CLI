<?php
/**
 * Created by PhpStorm.
 * User: zxm
 * Date: 11/4/15
 * Time: 2:06 PM
 */

namespace Clout\Commands;
use Clout\Users\UserService;

class HelpCommand extends UserServiceCommand
{
    const NAME = 'help';
    const PATTERN = '/^help$/i';

    public function parse($input) {
        $this->arguments[] = strcasecmp($input, 'help');
    }
    public function execute(UserService $userService) {
        if ($this->arguments[0] == 0) {
            echo "We support commands: 1. follows 2. clout 'user' 3. clout  4. help  5 exit \n";
            echo "set following relations: user follows user e.g. Messi follows Xavi \n";
            echo "Get influence of a user: clout user e.g. clout Xavi \n";
            echo "Get influence of all users: clout  e.g. clout \n";
            echo "Get tips of commands: help e.g. help \n";
            echo "Exit the CLI: exit e.g. exit \n";

        } else {
            echo "Type help to see the tips of commands \n";
        }
    }
}