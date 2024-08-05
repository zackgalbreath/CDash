<?php

namespace App\Ldap;

class User extends \LdapRecord\Models\OpenLDAP\User
{
    protected string $guidKey = 'uid';

//    public function __construct(array $attributes = [])
//    {
//        $this->guidKey = env('LDAP_GUID_KEY', $this->guidKey);
//
//        parent::__construct($attributes);
//    }
}
