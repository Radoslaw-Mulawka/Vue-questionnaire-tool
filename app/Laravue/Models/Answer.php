<?php


namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{

    protected static function boot()
    {
        parent::boot();
    }

    public function question()
    {
        return $this->hasOne('App\Laravue\Models\Question', 'id', 'questions_id');
    }

    public function option()
    {
        return $this->hasOne('App\Laravue\Models\Option', 'id', 'options_id');
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
    public function getCampaignsId(): int
    {
        return $this->campaigns_id;
    }

    /**
     * @param int $campaigns_id
     * @return Answer
     */
    public function setCampaignsId(int $campaigns_id): self
    {
        $this->campaigns_id = $campaigns_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getPlaceId(): int
    {
        return $this->place_id;
    }

    /**
     * @param int $place_id
     * @return Answer
     */
    public function setPlaceId(int $place_id): self
    {
        $this->place_id = $place_id;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getQuestionsId(): ?int
    {
        return $this->questions_id;
    }

    /**
     * @param int|null $questions_id
     * @return Answer
     */
    public function setQuestionsId(?int $questions_id): self
    {
        $this->questions_id = $questions_id;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getOptionsId(): ?int
    {
        return $this->options_id;
    }

    /**
     * @param int|null $options_id
     * @return Answer
     */
    public function setOptionsId(?int $options_id): self
    {
        $this->options_id = $options_id;
        return $this;

    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param string|null $value
     * @return Answer
     */
    public function setValue(?string $value): self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getGuestPhid(): string
    {
        return $this->guest_phid;
    }

    /**
     * @param string $guest_phid
     * @return Answer
     */
    public function setGuestPhid(string $guest_phid): self
    {
        $this->guest_phid = $guest_phid;
        return $this;
    }

    /**
     * @return string
     */
    public function getGuestIp(): string
    {
        return $this->guest_ip;
    }

    /**
     * @param string $guest_ip
     * @return Answer
     */
    public function setGuestIp(string $guest_ip): self
    {
        $this->guest_ip = $guest_ip;
        return $this;
    }

}
