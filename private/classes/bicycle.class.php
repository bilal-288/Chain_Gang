<?php

class bicycle
{

    static protected $database;
    static protected $db_columns = [
        'id', 'brand', 'model', 'year', 'category', 'color', 'gender', 'price',
        'weight_kg', 'condition_id', 'description'
    ];
    static public  function set_database($database)
    {
        self::$database = $database;
    }

    static public function find_by_sql($sql)
    {
        $result = self::$database->query($sql);
        if (!$result) {
            exit("Database query failed.");
        }

        $object_array = [];
        while ($record = $result->fetch_assoc()) {
            $object_array[] = self::instantiate($record);
        }

        $result->free();

        return $object_array;
    }


    static public function find_all()
    {
        $sql = "select * from bicycles";
        return  self::find_by_sql($sql);
    }

    static public function find_by_id($id)
    {
        $sql = "select * from bicycles";
        $sql .= " where id='" . self::$database->escape_string($id) . "'";
        $obj_array = self::find_by_sql($sql);
        if (!empty($obj_array)) {
            return array_shift($obj_array);
        } else {
            return false;
        }
    }

    static protected function instantiate($record)
    {
        $object = new self([]);

        foreach ($record as $property => $value) {
            if (property_exists($object, $property)) {
                $object->$property = $value;
            }
        }
        return $object;
    }

    public function create()
    {
        $attributes = $this->sanitized_attributes();
        $sql = "INSERT INTO bicycles (";
        $sql .= join(', ', array_keys($attributes));
        $sql .= ") VALUES ('";
        $sql .= join("', '", array_values($attributes));
        $sql .= "')";

        $result = self::$database->query($sql);
        if ($result) {
            $this->id = self::$database->insert_id;
        }
        return $result;
    }

    public function update()
    {
        $attributes = $this->sanitized_attributes();
        $attribute_pairs = [];
        foreach ($attributes as $key => $value) {
            $attribute_pairs[] = "{$key}='{$value}'";
        }

    
        $sql = "update bicycles set ";
        $sql .= join(', ', $attribute_pairs);
        $sql .= " WHERE id='" . $this->id . "' ";
        $sql .= "limit 1";

        $result = self::$database->query($sql);

    // Check if the query was executed successfully
    if ($result) {
        echo "updated";
        redirect_to(url_for('/staff/bicycles/show.php?id=' . $this->id));
        // Handle success case
        // ...
    } else {
        echo "not updated";
        // Handle failure case
        // ...
    }
    }

    public function merge_attributes($args = [])
    {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }

    public function attributes()
    {
        $attributes = [];
        foreach (self::$db_columns as $column) {
            if ($column == 'id') {
                continue;
            }
            $attributes[$column] = $this->$column;
        }
        return $attributes;
    }

    protected function sanitized_attributes()
    {
        $sanitized = [];
        foreach ($this->attributes() as $key => $value) {
            $sanitized[$key] = self::$database->escape_string($value);
        }
        return $sanitized;
    }
    public $id;
    public $brand;
    public $model;
    public $year;
    public $category;
    public $color;
    public $description;
    public $gender;
    public $price;
    public $weight_kg;
    public $condition_id;

    public const CATEGORIES = ['Road', 'Mountain', 'Hybrid', 'Cruiser', 'City', 'BMX'];
    public const GENDERS = ['Mens', 'Womens', 'Unisex'];
    public const  CONDITION_OPTIONS = [
        1 => 'Beat up',
        2 => 'Decent',
        3 => 'Good',
        4 => 'Great',
        5 => 'Like New',
    ];


    public function __construct($args)
    {


        $this->brand = $args['brand'] ?? '';
        $this->model = $args['model'] ?? '';
        $this->year = $args['year'] ?? '';
        $this->category = $args['category'] ?? '';
        $this->color = $args['color'] ?? '';
        $this->description = $args['description'] ?? '';
        $this->gender = $args['gender'] ?? '';
        $this->price = $args['price'] ?? 0;
        $this->weight_kg = $args['weight_kg'] ?? 0.0;
        $this->condition_id = $args['condition_id'] ?? 3;
    }





    /**
     * Get the value of weight_kg
     */
    public function getWeight_kg()
    {
        return number_format($this->weight_kg, 2) . 'kg';
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
        $weight_lbs = floatval($this->weight_kg) * 2.2046226218;
        return number_format($weight_lbs, 2) . 'lbs';
    }

    /**
     * Set the value of weight_kg
     *
     * @return  self
     */
    public function setWeight_lbs($value)
    {
        $this->weight_kg = floatval($value) / 2.2046226218;
    }

    /**
     * Get the value of condition_id
     */
    public function condition()
    {
        if ($this->condition_id > 0) {
            return self::CONDITION_OPTIONS[$this->condition_id];
        } else {
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
        $formatted = '$' . $this->price;
        return $formatted;
    }

    public function name()
    {
        return "{$this->brand} {$this->model} {$this->year}";
    }
}
