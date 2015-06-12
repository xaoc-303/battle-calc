<?php namespace Xaoc303\BattleCalc;

//use Illuminate\Support\Facades\Config;

/**
 * Class Unit
 * @package Xaoc303\BattleCalc
 */
class Unit
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    public $race_id;

    /**
     * @var int
     */
    public $init;

    /**
     * @var int
     */
    public $type;

    /**
     * @var int
     */
    public $bio;

    /**
     * @var int
     */
    public $shield;

    /**
     * @var int
     */
    public $armor;

    /**
     * @var int
     */
    public $hp;

    /**
     * @var int
     */
    public $mround;

    /**
     * @var int
     */
    public $cool;

    /**
     * @var int
     */
    public $attack_ter;

    /**
     * @var int
     */
    public $attack_air;

    /**
     * @var int
     */
    public $attack_magic;

    /**
     * @var null|string
     */
    public $magic1;

    /**
     * @var null|string
     */
    public $magic2;

    /**
     * @var null|string
     */
    public $magic3;

    /**
     * findById
     *
     * @param int $id
     * @return null|Unit
     */
    public function findById($id)
    {
        $unit = $this->findByField('id', $id);
        if (empty($unit)) {
            return null;
        }

        $unit = array_shift($unit);
        if (empty($unit)) {
            return null;
        }

        return $this->create($unit);
    }

    /**
     * findByRaceId
     *
     * @param int $id
     * @return array|null
     */
    public function findByRaceId($id)
    {
        $units = $this->findByField('race_id', $id);
        if (empty($units)) {
            return null;
        }

        $return_units = [];
        foreach ($units as $unit) {
            $return_units[] = $this->create($unit);
        }
        return $return_units;
    }

    /**
     * findByField
     *
     * @param string $field_key
     * @param mixed $field_value
     * @return array
     */
    private function findByField($field_key, $field_value)
    {
        $units = $this->getUnits();
        return array_where($units, function ($key, $value) use ($field_key, $field_value) {
            return $value[$field_key] == $field_value;
        });
    }

    /**
     * getUnits
     *
     * @return array
     */
    public function getUnits()
    {
        return Config::get('battle-calc::units');
    }

    /**
     * create
     *
     * @param array $unit_params
     * @return null|Unit
     */
    private function create($unit_params)
    {
        $vars = get_class_vars(get_class($this));

        $unit = new Unit();
        foreach ($unit_params as $key => $value) {
            if (! array_key_exists($key, $vars)) {
                return null;
            }

            $unit->$key = $value;
        }
        return $unit;
    }
}
