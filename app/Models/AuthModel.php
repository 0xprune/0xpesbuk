<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthModel extends Model
{
    protected $table = 'users'; // Adjust the table name based on your database setup
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'email', 'password', 'name'];

    public function getUserByUsername($username)
    {
        return $this->where('username', $username)->first();
    }

    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }
    public function registerUser($username, $email, $password, $name)
    {
        $ionAuth = new \IonAuth\Libraries\IonAuth();

        // Hash the password using IonAuth's hashPassword method
        $hashedPassword = $ionAuth->hashPassword($password);

        // Insert the user into the database with the hashed password
        $this->db->table('users')->insert([
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword,
            'name' => $name,
        ]);
    }

    // Add any additional methods you may need for your application
}
