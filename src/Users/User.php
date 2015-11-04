<?php
/**
 * Created by PhpStorm.
 * User: zxm
 * Date: 11/4/15
 * Time: 1:51 PM
 */

namespace Clout\Users;


class User
{
    private $name; //unique identifier of a user
    private $followers; //key-value (name -> User) pairs of the followers;
    private $followee; //one user could only follow another one and only one user


    public function __construct($name) {
        $this->name = $name;
        $this->followers = array();

    }


    /**
     * @param User $follower
     */
    public function addFollower(User $follower) {
        $this->followers[$follower->name] = $follower;
    }

    /**
     * @param User $followee
     */
    public function addFollowee(User $followee) {
        $this->followees[$followee->name] = $followee;
    }

    /**
     * @param $followerName
     * @return bool
     */
    public function ifFollowerExist($followerName) {
        return array_key_exists($followerName, $this->followers);
    }

    /**
     * @param $followeeName
     * @return bool
     */
    public function ifFolloweeExist($followeeName) {
        return array_key_exists($followeeName, $this->followees);
    }

    public function removeFollower($followerName) {
        unset($this->followers[$followerName]);
    }
    /**Getters and Setters*/

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getFollowers()
    {
        return $this->followers;
    }

    /**
     * @return mixed
     */
    public function getFollowee()
    {
        return $this->followee;
    }

    /**
     * @return Integer
     */
    public function getNumOfFollowers() {
        return count($this->followers);
    }

    /**
     * @return Integer
     */
    public function getNumOfFollowees() {
        return count($this->followees);
    }

    /**
     * @param mixed $followee
     */
    public function setFollowee($followee)
    {
        $this->followee = $followee;
    }

    /**
     * @param array $followers
     */
    public function setFollowers($followers)
    {
        $this->followers = $followers;
    }


}