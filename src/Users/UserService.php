<?php
/**
 * Created by PhpStorm.
 * User: zxm
 * Date: 11/4/15
 * Time: 1:48 PM
 */

namespace Clout\Users;

use Clout\Users\User;
use Clout\Users\UserSet;

class UserService
{
    private $userMap; // user name => user
    private $influenceMap; // user name => influence
    private $userSet; //user father set

    public function __construct()
    {
        $this->userMap = array();
        $this->influenceMap = array();
        $this->userSet = new UserSet();
    }



    public function setUser($userName)
    {
        $user = new User($userName);
        $this->userMap[$userName] = $user;
    }


    public function getUser($userName) {
        return $this->userMap[$userName];
    }

    public function removeUser($userName)
    {
        unset($this->userMap[$userName]);
    }


    public function hasUser($userName)
    {
        return array_key_exists($userName, $this->userMap);
    }


    public function follows($followerName, $followeeName)
    {
        if (strcasecmp($followerName, $followeeName) == 0) throw new \RuntimeException("A user cannot follow himself or herself \n");
        if (!$this->hasUser($followerName)) {
            $this->setUser($followerName);
            echo "New user ".$followerName." created! \n";
            $this->userSet->addUser($followerName);
        }
        if (!$this->hasUser($followeeName)) {
            $this->setUser($followeeName);
            echo "New user ".$followeeName." created! \n";
            $this->userSet->addUser($followeeName);
        }

        $follower = $this->getUser($followerName);
        $followee = $this->getUser($followeeName);

        $this->followsHelper($follower, $followee);


    }


    private function followsHelper(User $follower, User $followee) {
        if ($follower->getFollowee() === $followee) return;
        //update follower and followee information
        $oldFollowee = $follower->getFollowee();
        if(! empty($oldFollowee)) {
            echo "old followee is". $oldFollowee->getName();
            $oldFollowee->removeFollower($follower->getName());
            $bigFatherName = $this->userSet->compressFindBigFather($oldFollowee->getName());
            echo "The old followee's big father is ".$bigFatherName." \n";
            $this->updateInfluenceOfUsers($this->getUser($bigFatherName));
        }
        $follower->setFollowee($followee);
        $followee->addFollower($follower);
        $this->userSet->unionSets($follower->getName(), $followee->getName());
        //find big father of the followee and update influence of the big father and its followers in range of influence
        $bigFatherName = $this->userSet->compressFindBigFather($followee->getName());
        echo "The big father is ".$bigFatherName." \n";
        $this->updateInfluenceOfUsers($this->getUser($bigFatherName));
    }



    private function updateInfluenceOfUsers(User $user) {
        $visited = array();
        $path = array();
        $loop = array();
        $influence = $this->updateInfluenceOfUsersHelper($user, $path, $visited, $loop) - 1;
        echo "The loop size is ".count($loop);
        if (count($loop) == 0) {
            $this->setUserInfluence($user->getName(), $influence);
        } else {
            foreach (array_keys($loop) as $name) {
                $this->setUserInfluence($name, $influence);
            }
        }
    }

    private function updateInfluenceOfUsersHelper(User $user, &$path, &$visited, &$loop) {
        if (is_null($user)) {
            return 0;
        }
        if (array_key_exists($user->getName(), $path)) {
            $loop = $path;
            return 0;
        }
        if(array_key_exists($user->getName(), $visited)) {
            return 0;
        }
        $name = $user->getName();
        $visited[$name] = $user;
        $path[$name] = $user;
        $influence = 0;
        foreach (array_values($user->getFollowers()) as $follower) {
            echo "traverse follower ". $follower->getName(). "\n";
            $influence += $this->updateInfluenceOfUsersHelper($follower, $path, $visited, $loop);
        }
        unset($path[$name]);
        $this->setUserInfluence($user->getName(), $influence);

        return $influence + 1;
    }



    private function setUserInfluence($userName, $influence) {
        $this->influenceMap[$userName] = $influence;
    }


    public function getUserInfluence($userName) {
        if (! $this->hasUser($userName)) throw new RuntimeException("The user does not exist!");
        return $this->influenceMap[$userName];
    }


    public function getInfluenceOfAllUsers()
    {
        return $this->influenceMap;
    }

}