<?php
/**
 * Created by PhpStorm.
 * User: zxm
 * Date: 11/4/15
 * Time: 1:53 PM
 */

namespace Clout\Users;


class UserSet
{
    private $bigFatherMap = array();

    public function contains($userName) {
        return array_key_exists($userName, $this->bigFatherMap);
    }

    public function addUser($userName) {
        $this->setBigFather($userName, $userName);
    }

    public function setBigFather($userName, $bigFatherName) {
        $this->bigFatherMap[$userName] = $bigFatherName;
    }
    public function compressFindBigFather($userName) {
        if (! $this->contains($userName)) throw new \RuntimeException("The user does not exist");
        $fatherName = $this->bigFatherMap[$userName];
        if (strcasecmp($userName, $fatherName) == 0) return $userName;
        $bigFatherName = $this->compressFindBigFather($fatherName);
        $this->setBigFather($userName, $bigFatherName);

        return $bigFatherName;
    }

    public function unionSets($followerName, $followeeName) {
        $bigFatherOfFollower = $this->compressFindBigFather($followerName);
        $bigFatherOfFollowee = $this->compressFindBigFather($followeeName);
        $this->setBigFather($bigFatherOfFollower, $bigFatherOfFollowee);
    }

}