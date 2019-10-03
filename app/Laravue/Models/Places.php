<?php


namespace App\Laravue\Models;

use App\Scopes\PlaceScope;
use Illuminate\Database\Eloquent\Model;

class Places extends Model
{
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new PlaceScope);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getUsersId(): int
    {
        return $this->users_id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param int $users_id
     */
    public function setUsersId(int $users_id): void
    {
        $this->users_id = $users_id;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }


}
