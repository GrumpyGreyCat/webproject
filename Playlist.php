<?php

/**
 * Class Playlist
 * 
 * Represents a music playlist with basic properties and methods for manipulation.
 */
class Playlist
{
    public $id;
    public $name;
    public $user_id;
    public $created_at;
    public $updated_at;

    /**
     * Constructor for Playlist class.
     * 
     * @param array $data An associative array containing initial data for the playlist.
     */
    public function __construct(array $data)
    {
        $this->hydrate($data);
    }

    /**
     * Populates object properties using an associative array.
     * 
     * @param array $data An associative array where keys are property names and values are property values.
     */
    public function hydrate(array $data): void
    {
        foreach ($data as $key => $value) {
            $method = "set" . ucfirst($key); // setId, setName, etc.
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    /**
     * Set the playlist ID.
     * 
     * @param mixed $id The ID to set.
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * Set the playlist name.
     * 
     * @param string $name The name to set.
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * Set the user ID associated with the playlist.
     * 
     * @param mixed $user_id The user ID to set.
     */
    public function setUser_id($user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * Set the creation date of the playlist.
     * 
     * @param mixed $created_at The creation date to set.
     */
    public function setCreated_at($created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * Set the last updated date of the playlist.
     * 
     * @param mixed $updated_at The updated date to set.
     */
    public function setUpdated_at($updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    /**
     * Get the playlist ID.
     * 
     * @return mixed The playlist ID.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the playlist name.
     * 
     * @return string The playlist name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the user ID associated with the playlist.
     * 
     * @return mixed The user ID.
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Get the creation date of the playlist.
     * 
     * @return mixed The creation date.
     */
    public function getCreated_at()
    {
        return $this->created_at;
    }

    /**
     * Get the last updated date of the playlist.
     * 
     * @return mixed The updated date.
     */
    public function getUpdated_at()
    {
        return $this->updated_at;
    }
}
