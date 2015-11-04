<?php
/**
 * Created by PhpStorm.
 * User: zxm
 * Date: 11/4/15
 * Time: 1:44 PM
 */

namespace Clout;

use Clout\Users\UserService;

class App
{
    private $userService;
    public function __construct() {
        $this->userService = new UserService();
    }
    public function run()
    {

        while (true) {
            echo "> ";
            $input = $this->getInput();
            echo "\n";
            $command = $this->getValidCommand($input);
            if (is_null($command)) {
                echo "Please input the correct commands. Type 'help' to see tips of commands \n";
            } else {
                $command->parse($input);
                $command->execute($this->userService);
            }

        }
    }

    private function getInput()
    {
        $handle = fopen("php://stdin", "r");
        $input = fgets($handle);
        $input = trim($input);

        return $input;
    }

    private function getValidCommand($input)
    {
        if (FollowsCommand::isCommand($input)) {
            return new FollowsCommand();
        } else if (CloutCommand::isCommand($input)) {
            return new CloutCommand();
        } else if (HelpCommand::isCommand($input)) {
            return new HelpCommand();
        } else if (ExitCommand::isCommand($input)) {
            return new ExitCommand();
        } else return null;
    }
}