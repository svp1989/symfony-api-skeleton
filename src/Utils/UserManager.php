<?php
namespace App\Utils;
use FOS\UserBundle\Doctrine\UserManager as Manager;
class UserManager extends Manager
{
    /**
     * @inheritdoc
     */
    public function findUserByUsernameOrEmail($usernameOrEmail)
    {
        return parent::findUserByUsernameOrEmail($usernameOrEmail);
    }
}
