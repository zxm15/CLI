<?php

namespace Clout\Users;


use Doctrine\Instantiator\Exception\UnexpectedValueException;

class UserSet
{
    private $bigFatherMap = array();

    /**
     * @param $userName
     * @return bool
     */
    public function contains($userName) {
        return array_key_exists($userName, $this->bigFatherMap);
    }

    /**
     * @param $userName
     */
    public function addUser($userName) {
        $this->setBigFather($userName, $userName);
    }

    /**
     * @param $userName
     * @param $bigFatherName
     */
    public function setBigFather($userName, $bigFatherName) {
        $this->bigFatherMap[$userName] = $bigFatherName;
    }

    /**
     * Search for the big father and update users big father in the path
     * @param $userName
     * @return null
     */
    public function compressFindBigFather($userName) {
        if (! $this->contains($userName)) return null;
        $fatherName = $this->bigFatherMap[$userName];
        if (strcasecmp($userName, $fatherName) == 0) return $userName;
        $bigFatherName = $this->compressFindBigFather($fatherName);
        $this->setBigFather($userName, $bigFatherName);

        return $bigFatherName;
    }

    /**
     * Union two sets connected with two users
     * @param $followerName
     * @param $followeeName
     */
    public function unionSets($followerName, $followeeName) {
        $bigFatherOfFollower = $this->compressFindBigFather($followerName);
        $bigFatherOfFollowee = $this->compressFindBigFather($followeeName);
        if ($bigFatherOfFollowee == null || $bigFatherOfFollowee == null)
            throw new UnexpectedValueException("Dude, user does not exist \n");
        $this->setBigFather($bigFatherOfFollower, $bigFatherOfFollowee);
    }

    /**
     * @return array
     */
    public function getBigFatherMap()
    {
        return $this->bigFatherMap;
    }



}