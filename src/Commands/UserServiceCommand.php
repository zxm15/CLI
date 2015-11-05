<?php

namespace Clout\Commands;

use Clout\Users\UserService;

abstract class UserServiceCommand
{
    const NAME = '';
    const PATTERN = '';
    protected $arguments = array();

    /**
     * Check if input is this command
     * @param $input
     * @return int
     */
    public static function isCommand($input) {
        if(preg_match(static::PATTERN, $input) == 1) return true;
        return false;
    }

    /**
     * Parse input to get arguments
     * @param $input
     */
    public function parse($input) {
        $this->arguments = preg_split(static::PATTERN, $input);
        if (count($this->arguments) == 2) {
            $this->arguments[0] = trim($this->arguments[0]);
            $this->arguments[1] = trim($this->arguments[1]);
        }
    }

    /**
     * Execute command to handle requests
     * @param UserService $userService
     * @return mixed
     */
    public abstract function execute(UserService $userService);

    /**
     * @return array   parameters of users input
     */
    public function getArguments()
    {
        return $this->arguments;
    }


}