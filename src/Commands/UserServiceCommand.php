<?php
/**
 * Created by PhpStorm.
 * User: zxm
 * Date: 11/4/15
 * Time: 1:57 PM
 */

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
        return preg_match(static::PATTERN, $input);
    }

    /**
     * Parse input to get arguments
     * @param $input
     */
    public function parse($input) {
        $this->arguments = preg_split(static::PATTERN, $input);
    }

    /**
     * Execute command to handle requests
     * @param UserService $userService
     * @return mixed
     */
    public abstract function execute(UserService $userService);
}