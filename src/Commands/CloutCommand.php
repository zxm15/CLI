<?php
/**
 * Created by PhpStorm.
 * User: zxm
 * Date: 11/4/15
 * Time: 2:04 PM
 */

namespace Clout\Commands;

use Clout\Users\UserService;

class CloutCommand extends UserServiceCommand
{
    const NAME = 'clout';
    const PATTERN = '/^clout\s*/i';

    /**
     * Answer clout request to get influence of either a single user or all users
     * @param UserService $userService
     * @throws \Clout\Users\RuntimeException
     */
    public function execute(UserService $userService) {
        if (count($this->arguments) == 2) {
            if (strlen($this->arguments[1]) > 0) {
                echo "Clout user ".$this->arguments[1]." \n";
                $user = $this->arguments[1];
                $influence = $userService->getUserInfluence($user);
                echo $user." has ".$influence." followers \n";

            } else {
                echo "Clout all users \n";
                $influenceArray = $userService->getInfluenceOfAllUsers();
                foreach ($influenceArray as $user => $influence) {
                    echo $user." has ".$influence." followers \n";
                }
            }

        } else {
            echo "Please write the clout command in correct form. e.g. \n";
            echo "Get influence of a single user: clout name     e.g. clout Messi \n";
            echo "Get influence of all users: clout     e.g. clout \n";
        }
    }
}