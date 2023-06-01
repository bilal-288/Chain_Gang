<?php

class bicycle
{
    public $brand;
    public $model;
    public $year;
    public $category;
    public $color;
    public $description;
    public $gender;
    public $price;
    protected $weight_kg;
    protected $condition_id;

    public const CATEGORIES= ['Road','Mountain','Hybrid','Cruiser','City','BMX'];
    public const GENDERS= ['Mens', 'Womens', 'Unisex'];
    protected const  CONDITION_OPTIONS=[
        1 => 'Beat up',
        2 => 'Decent',
        3 => 'Good',
        4 => 'Great',
        5 => 'Like New',
    ];


    public function __construct($args)
    {

        $this->brand =$args['brand'] ?? '';
        $this->model =$args['model'] ?? '';
        $this->year =$args['year'] ?? '';
        $this->category =$args['category'] ?? '';
        $this->color =$args['color'] ?? '';
        $this->description =$args['description'] ?? '';
        $this->gender =$args['gender'] ?? '';
        $this->price =$args['price'] ?? 0;
        $this->weight_kg =$args['weight_kg'] ?? 0.0;
        $this->condition_id =$args['condition_id'] ?? 3;
        
    }





    /**
     * Get the value of weight_kg
     */ 
    public function getWeight_kg()
    {
        return number_format($this->weight_kg , 2). 'kg';
    }

    /**
     * Set the value of weight_kg
     *
     * @return  self
     */ 
    public function setWeight_kg($weight_kg)
    {
        $this->weight_kg = floatval($weight_kg);

        return $this;
    }

    /**
     * Get the value of weight_lbs
     */ 
    public function getWeight_lbs()
    {
        $weight_lbs = floatval($this->weight_kg)* 2.2046226218;
        return number_format($weight_lbs , 2). 'lbs';
    }

    /**
     * Set the value of weight_kg
     *
     * @return  self
     */ 
    public function setWeight_lbs($value)
    {
        $this->weight_kg = floatval($value)/2.2046226218;
    }

    /**
     * Get the value of condition_id
     */ 
    public function condition()
    {
        if($this->condition_id > 0)
        {
            return self::CONDITION_OPTIONS[$this->condition_id];
        }else{
            return "Unknown";
        }

        return $this->condition_id;
    }

    

    /**
     * Set the value of condition_id
     *
     * @return  self
     */ 
    public function setCondition_id($condition_id)
    {
        $this->condition_id = $condition_id;

        return $this;
    }

    public function price_format()
    {
        $formatted = '$'. $this->price;
        return $formatted;
    }
}
?>