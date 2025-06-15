<?php

/**
 * Class User
 * 
 * Represents a user of the application, with properties and methods for manipulation.
 */
class User {
    /**
     * @var int The user ID (primary key in the database).
     */
    private $id;

    /**
     * @var string The user's username.
     */
    private $username;

    /**
     * @var string The user's email address.
     */
    private $email;

    /**
     * @var string The user's password hash.
     */
    private $password_hash;

    /**
     * @var string The user's role (e.g. "user", "admin").
     */
    private $role;

    /**
     * Constructor for User class.
     * 
     * @param array $data An associative array containing initial data for the user.
     */
    public function __construct(array $data)
    {
        $this->hydrate($data);
    }

    /**
     * Populate the user object using an associative array.
     * 
     * @param array $data An associative array where keys are property names and values are property values.
     */
    public function hydrate(array $data): void
    {
        foreach ($data as $key => $value) {
            $method = "set" . ucfirst($key); // setId, setNumber, setName, etc.
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    /**
     * Set the user ID.
     * 
     * @param int $id The ID to set.
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * Set the user's username.
     * 
     * @param string $username The username to set.
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * Set the user's email address.
     * 
     * @param string $email The email address to set.
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * Set the user's password hash.
     * 
     * @param string $password_hash The password hash to set.
     */
    public function setPassword_hash($password_hash): void
    {
        $this->password_hash = $password_hash;
    }

    /**
     * Set the user's role.
     * 
     * @param string $role The role to set (e.g. "user", "admin").
     */
    public function setRole($role): void
    {
        $this->role = $role;
    }

    /**
     * Get the user ID.
     * 
     * @return int The user ID.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the user's username.
     * 
     * @return string The user's username.
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get the user's email address.
     * 
     * @return string The user's email address.
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get the user's password hash.
     * 
     * @return string The user's password hash.
     */
    public function getPassword_hash()
    {
        return $this->password_hash;
    }

    /**
     * Get the user's role.
     * 
     * @return string The user's role (e.g. "user", "admin").
     */
    public function getRole()
    {
        return $this->role;
    }
}
