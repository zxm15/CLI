<?php


namespace Clout\Commands;
use Clout\Users\UserService;

class HelpCommand extends UserServiceCommand
{
    const NAME = 'help';
    const PATTERN = '/^help$/i';

    /**
     * @param $input
     */
    public function parse($input) {
        $this->arguments[] = $input =='help';
    }

    /**
     * Help command
     * @param UserService $userService
     */
    public function execute(UserService $userService) {
        if ($this->arguments[0] == 1) {
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