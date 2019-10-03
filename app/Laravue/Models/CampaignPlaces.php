<?php


namespace App\Laravue\Models;


use Illuminate\Database\Eloquent\Model;

class CampaignPlaces extends Model
{
    public $timestamps = false;
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
     * @param int $users_id
     */
    public function setUsersId(int $users_id): void
    {
        $this->users_id = $users_id;
    }

    /**
     * @return int
     */
    public function getPlacesId(): int
    {
        return $this->places_id;
    }


    /**
     * @param int $places_id
     */
    public function setPlacesId(int $places_id): void
    {
        $this->places_id = $places_id;
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
     */
    public function setCampaignsId(int $campaigns_id): void
    {
        $this->campaigns_id = $campaigns_id;
    }

    /**
     * @return string
     */
    public function getShortcode(): string
    {
        return $this->shortcode;
    }


    /**
     * @param string $shortcode
     */
    public function setShortcode(string $shortcode): void
    {
        $this->shortcode = $shortcode;
    }


    /**
     * @return string
     */
    public function getLabelName(): string
    {
        return $this->label_name;
    }

    /**
     * @param string $label_name
     */
    public function setLabelName(string $label_name): void
    {
        $this->label_name = $label_name;
    }


}
