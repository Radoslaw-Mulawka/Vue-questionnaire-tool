<?php


namespace App\Laravue\Models;

use App\Scopes\OptionScope;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new OptionScope());
    }

    /**
     * Get the question for the campaign.
     */
    public function questions()
    {
        return $this->belongsTo('App\Laravue\Models\Question', 'questions_id');
    }

    public function campaign()
    {
        return $this->belongsTo('App\Laravue\Models\Campaign', 'campaigns_id');
    }

    /**
     * @return int
     */
    public function getId() :int
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
     * @param int $users_id
     * @return Option
     */
    public function setUsersId(int $users_id): self
    {
        $this->users_id = $users_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getCampaignsId(): int
    {
        return $this->campaigns_id;
    }

    /**
     * @param int $campaigns_id
     * @return Option
     */
    public function setCampaignsId(int $campaigns_id): self
    {
        $this->campaigns_id = $campaigns_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuestionsId(): int
    {
        return $this->questions_id;
    }

    /**
     * @param int $questions_id
     * @return Option
     */
    public function setQuestionsId(int $questions_id): self
    {
        $this->questions_id = $questions_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return Option
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return $this->order;
    }

    /**
     * @param int $order
     * @return Option
     */
    public function setOrder(int $order): self
    {
        $this->order = $order;
        return $this;
    }

}
