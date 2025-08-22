<?php

class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password', 'role', 'status'];

    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function createUser($data)
    {
        if (isset($data['password'])) {
            $data['password'] = $this->hashPassword($data['password']);
        }

        return $this->create($data);
    }
}
