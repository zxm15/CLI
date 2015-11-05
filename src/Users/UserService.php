<?php


namespace Clout\Users;

use Clout\Users\User;
use Clout\Users\UserSet;

class UserService
{

    private $userMap; //  user name => user

    private $influenceMap; // user name => influence

    private $userSet; //user big father   user name => big father name


    public function __construct()
    {
        $this->userMap = array();
        $this->influenceMap = array();
        $this->userSet = new UserSet();
    }


    /**
     * Add user
     * @param $userName
     */
    public function setUser($userName)
    {
        $user = new User($userName);
        $this->userMap[$userName] = $user;
        echo "New user ".$userName." created! \n";
        $this->userSet->addUser($userName);
        $this->setUserInfluence($userName, 0);
    }


    /**
     * @param $userName
     * @return mixed
     */
    public function getUser($userName) {
        return $this->userMap[$userName];
    }


    /**
     * Check if a user exists
     * @param $userName
     * @return bool
     */
    public function hasUser($userName)
    {
        return array_key_exists($userName, $this->userMap);
    }


    /**
     * Update following relation between two users; update influences of them and their connected users
     * @param $followerName
     * @param $followeeName
     */
    public function follows($followerName, $followeeName)
    {
        if (strcasecmp($followerName, $followeeName) == 0) throw new \RuntimeException("A user cannot follow himself or herself \n");
        //Create new users if not existed
        if (!$this->hasUser($followerName)) {
            $this->setUser($followerName);
        }
        if (!$this->hasUser($followeeName)) {
            $this->setUser($followeeName);
        }

        $follower = $this->getUser($followerName);
        $followee = $this->getUser($followeeName);

        $this->followsHelper($follower, $followee);


    }


    /**
     * For a new following relations, update old and new followees and their connected users influence and connected set
     * @param \Clout\Users\User $follower
     * @param \Clout\Users\User $followee
     */
    private function followsHelper(User $follower, User $followee) {

        if ($follower->getFollowee() === $followee) return;
        $oldFollowee = $follower->getFollowee();
        //update old followee's influence and big father
        if(! empty($oldFollowee)) {
            $oldFollowee->removeFollower($follower->getName());
            $bigFatherName = $this->userSet->compressFindBigFather($oldFollowee->getName());
            $this->updateInfluenceOfUsers($this->getUser($bigFatherName));
        }
        //update new followee's influence and big father
        $follower->setFollowee($followee);
        $followee->addFollower($follower);
        try {
            $this->userSet->unionSets($follower->getName(), $followee->getName());
        } catch (\Exception $e){
            echo $e->getMessage();
        }

        $bigFatherName = $this->userSet->compressFindBigFather($followee->getName());
        $this->updateInfluenceOfUsers($this->getUser($bigFatherName));
    }


    /**
     * Depth first traversal to update influences of the user; Update loop if there is any
     * @param \Clout\Users\User $user
     */
    private function updateInfluenceOfUsers(User $user) {
        $visited = array();
        $path = array();
        $loop = array();
        $influence = $this->updateInfluenceOfUsersHelper($user, $path, $visited, $loop) - 1;
        //echo "The loop size is ".count($loop);
        if (count($loop) == 0) {
            $this->setUserInfluence($user->getName(), $influence);
        } else {
            foreach (array_keys($loop) as $name) {
                $this->setUserInfluence($name, $influence);
            }
        }
    }

    /**
     *
     * @param \Clout\Users\User $user
     * @param $path
     * @param $visited
     * @param $loop
     * @return int
     */
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
            //echo "traverse follower ". $follower->getName(). "\n";
            $influence += $this->updateInfluenceOfUsersHelper($follower, $path, $visited, $loop);
        }
        unset($path[$name]);
        $this->setUserInfluence($user->getName(), $influence);

        return $influence + 1;
    }


    /**
     * @param $userName
     * @param $influence
     */
    private function setUserInfluence($userName, $influence) {
        $this->influenceMap[$userName] = $influence;
    }


    /**
     * @param $userName
     * @return mixed
     * @throws \RuntimeException
     */
    public function getUserInfluence($userName) {
        if (! $this->hasUser($userName)) throw new \RuntimeException("The user does not exist.");
        return $this->influenceMap[$userName];
    }


    /**
     * @return array
     */
    public function getInfluenceOfAllUsers()
    {
        return $this->influenceMap;
    }

    /**
     * @return array
     */
    public function getUserMap()
    {
        return $this->userMap;
    }

    /**
     * @return array
     */
    public function getInfluenceMap()
    {
        return $this->influenceMap;
    }

    /**
     * @return \Clout\Users\UserSet
     */
    public function getUserSet()
    {
        return $this->userSet;
    }



}