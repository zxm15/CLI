<?php
/**
 * Created by PhpStorm.
 * User: zxm
 * Date: 11/4/15
 * Time: 2:01 PM
 */

namespace Clout\Commands;

use Clout\Users\UserService;

class FollowsCommand extends UserServiceCommand
{
    const NAME = 'follows';
    const PATTERN = '/\s+follows\s+/i';

    public function execute(UserService $userService) {
        if (count($this->arguments) == 2 && strlen($this->arguments[0]) > 0 && strlen($this->arguments[1]) > 0) {
//            echo "The follower is ". $this->arguments[0]."\n";
//            echo "The followee is ". $this->arguments[1]."\n";
            $followerName = $this->arguments[0];
            $followeeName = $this->arguments[1];
            $userService->follows($followerName, $followeeName);

        } else {
            echo "Please input your commands correctly. e.g. Messi follows Xavi\n";
        }
    }
}