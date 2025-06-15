<?php

/**
 * Class UserController
 *
 * Handles database operations for users.
 */
class UserController 
{
    /** @var PDO $db The database connection */
    private PDO $db;
    
    /**
     * Constructs a new UserController
     */
    public function __construct()
    {
        $dbHost = 'localhost';
        $dbName = 'sonatina';
        $dbPort = 3306;
        $dbUser = 'root';
        $dbPass = '';

        try {
            $dsn = "mysql:host=$dbHost;dbname=$dbName;port=$dbPort;charset=utf8mb4";
            $this->db = new PDO($dsn, $dbUser, $dbPass);
        } catch (PDOException $error) {
            error_log("Database connection error: " . $error->getMessage());
            die("Connection failed: " . $error->getMessage());
        }
    }

    /**
     * Sets the database connection
     * 
     * @param PDO $db The database connection
     */
    public function setDb(PDO $db): void
    {
        $this->db = $db;
    }

    /**
     * Creates a new user
     * 
     * @param User $user The user to create
     */
    public function createUser(User $user): void
    {
        $sql = "INSERT INTO users (username, email, password_hash, role) VALUES (:username, :email, :password_hash, :role)";
        $req = $this->db->prepare($sql);
        $req->bindValue(':username', $user->getUsername());
        $req->bindValue(':email', $user->getEmail());
        $req->bindValue(':password_hash', $user->getPassword_hash());
        $req->bindValue(':role', $user->getRole());
        $req->execute();
    }

    /**
     * Deletes a user
     * 
     * @param User $user The user to delete
     */
    public function deleteUser(User $user): void
    {
        $sql = "DELETE FROM users WHERE id = :id";
        $req = $this->db->prepare($sql);
        $req->bindValue(':id', $user->getId());
        $req->execute();
    }

    /**
     * Returns all users
     * 
     * @return array The list of users
     */
    public function readAllUser(): array
    {
        $sql = "SELECT * FROM users";
        $req = $this->db->prepare($sql);
        $req->execute();
        $users = $req->fetchAll(PDO::FETCH_ASSOC);
        return $users;
    }

    /**
     * Updates a user
     * 
     * @param User $user The user to update
     */
    public function updateUser(User $user): void
    {
        $sql = "UPDATE users SET username = :username, email = :email, password_hash = :password_hash, role = :role WHERE id = :id";
        $req = $this->db->prepare($sql);
        $req->bindValue(':id', $user->getId());
        $req->bindValue(':username', $user->getUsername());
        $req->bindValue(':email', $user->getEmail());
        $req->bindValue(':password_hash', $user->getPassword_hash());
        $req->bindValue(':role', $user->getRole());
        $req->execute();
    }

    /**
     * Returns a user by their ID
     * 
     * @param int $id The user ID
     * @return array|null The user data or null if not found
     */
    public function readUser($id): ?array
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        $req = $this->db->prepare($sql);
        $req->bindValue(':id', $id);
        $req->execute();
        $user = $req->fetch(PDO::FETCH_ASSOC);
        return $user ? $user : null;
    }

    /**
     * Returns a user by their email
     * 
     * @param string $email The user's email
     * @return User|null The user or null if not found
     */
    public function getUserByEmail($email): ?User
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $req = $this->db->prepare($sql);
        $req->bindValue(':email', $email);
        $req->execute();
        $user = $req->fetch();
        if (!$user) {
            return null;
        }
        return new User($user);
    }

    /**
     * Returns a user by their username
     * 
     * @param string $username The user's username
     * @return User|null The user or null if not found
     */
    public function getUserByUsername($username): ?User
    {
        $sql = "SELECT * FROM users WHERE username = :username";
        $req = $this->db->prepare($sql);
        $req->bindValue(':username', $username);
        $req->execute();
        $user = $req->fetch();
        if (!$user) {
            return null;
        }
        return new User($user);
    }

    /**
     * Returns the user ID by their username
     * 
     * @param string $username The user's username
     * @return array|null The user ID or null if not found
     */
    public function returnIdByUsername($username): ?array
    {
        $sql = "SELECT id FROM users WHERE username = :username";
        $req = $this->db->prepare($sql);
        $req->bindValue(':username', $username);
        $req->execute();
        $user = $req->fetch();
        return $user ? $user : null;
    }

    /**
     * Returns the user ID by their email
     * 
     * @param string $email The user's email
     * @return array|null The user ID or null if not found
     */
    public function returnIdByEmail($email): ?array
    {
        $sql = "SELECT id FROM users WHERE email = :email";
        $req = $this->db->prepare($sql);
        $req->bindValue(':email', $email);
        $req->execute();
        $user = $req->fetch();
        return $user ? $user : null;
    }
}
