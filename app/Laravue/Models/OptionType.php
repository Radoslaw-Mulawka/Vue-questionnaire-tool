<?php


namespace App\Laravue\Models;


use Illuminate\Database\Eloquent\Model;

class OptionType extends Model
{
    public $timestamps = false;

    /**
     * @return string
     */
    public function getName() :string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return OptionType
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription() :string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return OptionType
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getTypeExplain() :string
    {
        return $this->type_explain;
    }

    /**
     * @param string $type_explain
     * @return OptionType
     */
    public function setTypeExplain(string $type_explain): self
    {
        $this->type_explain = $type_explain;
        return $this;
    }

    /**
     * @return int
     */
    public function getCanAddOption() :int
    {
        return $this->can_add_option;
    }

    /**
     * @param int $can_add_option
     * @return OptionType
     */
    public function setCanAddOption(int $can_add_option): self
    {
        $this->can_add_option = $can_add_option;
        return $this;
    }

}
