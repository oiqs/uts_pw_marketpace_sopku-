<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
    public function find_by_email($email)
    {
        return $this->db->get_where('users', ['email' => $email])->row_array();
    }

    public function create($name, $email, $password)
    {
        return $this->db->insert('users', [
            'name'     => $name,
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role'     => 'user',
        ]);
    }

    public function verify($email, $password)
    {
        $user = $this->find_by_email($email);
        if (!$user) return null;

        // Support hash bcrypt (data baru) maupun plain text (data dummy awal)
        $valid = password_get_info($user['password'])['algo']
            ? password_verify($password, $user['password'])
            : ($password === $user['password']);

        if ($valid) {
            unset($user['password']);
            return $user;
        }
        return null;
    }
}
