<?php

class User extends Model
{
    public function getByLogin($login)
    {
        $login = $this->db->escape($login);
        $sql = "select * from users where login = '{$login}'";
        $result = $this->db->query($sql);
        if ( isset($result[0]) ){
            return $result[0];
        }
        return false;
    }

    public function registerUser($user)
    {
        $password = password_hash($user['password'], PASSWORD_DEFAULT);
        $sql ="insert into users
                    set email = '{$user['email']}',
                        login = '{$user['login']}',
                        password = '{$password}',
                        is_active = 1,
                        role = 'author',
                        first_name = '{$user['first_name']}',
                        second_name = '{$user['second_name']}'
               ";
         return $this->db->query($sql);
    }
}