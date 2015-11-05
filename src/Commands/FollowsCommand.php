<?php


namespace Clout\Commands;

use Clout\Users\UserService;

class FollowsCommand extends UserServiceCommand
{
    const NAME = 'follows';
    const PATTERN = '/\s+follows\s+/i';

    /**
     * Handle follows request
     * @param UserService $userService
     */
    public function execute(UserService $userService) {
        if (count($this->arguments) == 2 && strlen($this->arguments[0]) > 0 && strlen($this->arguments[1]) > 0) {
            $followerName = $this->arguments[0];
            $followeeName = $this->arguments[1];
            try {
                $userService->follows($followerName, $followeeName);
            } catch(\RuntimeException $e) {
                echo $e->getMessage();
            }

        } else {
            echo "Please input your commands correctly. e.g. Messi follows Xavi\n";
        }
    }
}