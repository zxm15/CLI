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

    public static function isCommand($input) {
        return preg_match(static::PATTERN, $input);
    }

    public function parse($input) {
        $this->arguments = preg_split(static::PATTERN, $input);
    }

    public abstract function execute(UserService $userService);
}