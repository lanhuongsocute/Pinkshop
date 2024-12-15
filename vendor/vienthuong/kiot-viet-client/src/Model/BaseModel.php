<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Model;

use VienThuong\KiotVietClient\Collection\Collection;
use VienThuong\KiotVietClient\Exception\KiotVietException;
use VienThuong\KiotVietClient\Utils\ExtendableTrait;
use VienThuong\KiotVietClient\Utils\ReadDocsHelper;

abstract class BaseModel
{
    use CommonFields;
    use ExtendableTrait;

    final public function __construct()
    {
        if (func_num_args() == 1 && is_array(func_get_arg(0))) {
            // Initialize the model with the array's contents.
            $modelData = func_get_arg(0);
            $this->mapTypes($modelData);
        }
    }

    /**
     * Initialize this object's properties from an array.
     *
     * @param array $properties Used to seed this object's properties.
     */
    protected function mapTypes(array $properties = []): void
    {
        foreach ($properties as $key => $val) {
            $ucFirst = ucfirst($key);
            $setter = 'set' . $ucFirst;
            $camelKey = $this->camelCase($key);
            $propertyType = null;

            if (property_exists($this, $key)) {
                $propertyType = ReadDocsHelper::detectPropertyType(get_class($this), $key);
            } elseif (property_exists($this, $camelKey)) {
                $propertyType = ReadDocsHelper::detectPropertyType(get_class($this), $camelKey);
            }

            if (!empty($propertyType) && class_exists($propertyType)) {
                $propertyParent = get_parent_class($propertyType);

                if ($propertyParent === BaseModel::class || $propertyParent === Collection::class) {
                    $val = new $propertyType($val);
                }
            }

            if (method_exists($this, $setter)) {
                $this->$setter($val);
                unset($properties[$key]);
            } elseif (property_exists($this, $camelKey)) {
                $setter = 'set' . $ucFirst;
                $this->$setter($val);
                unset($properties[$key]);
            }
        }

        $this->otherProperties = $properties;
    }

    /**
     * Convert a string to camelCase
     * @param  string $value
     * @return string
     */
    private function camelCase($value)
    {
        $value = ucwords(str_replace(array('-', '_'), ' ', $value));
        $value = str_replace(' ', '', $value);
        $value[0] = strtolower($value[0]);
        return $value;
    }

    /**
     * Reverse of mapTypes
     */
    public function getModelData(): array
    {
        $class = new \ReflectionClass($this);
        $properties = array_merge(
            $class->getProperties(\ReflectionProperty::IS_PRIVATE),
            $class->getProperties(\ReflectionProperty::IS_PROTECTED)
        );

        $data = [];

        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $getter = 'get' . ucfirst($propertyName);

            if (method_exists($this, $getter)) {
                $val = $this->$getter();

                if (!empty($val) && is_object($val)) {
                    if ($val instanceof Collection) {
                        $items = $val->toArray();
                        $val = [];
                        foreach ($items as $item) {
                            if ($item instanceof BaseModel) {
                                $val[] = $item->getModelData();
                            }
                        }
                    } elseif ($val instanceof BaseModel) {
                        $val = $val->getModelData();
                    }
                }
                $data[$propertyName] = $val;
            }
        }
        return $data;
    }

    /**
     */
    public function getProperty(string $key)
    {
        $getter = "get" . ucfirst($key);

        if (!property_exists(self::class, $key)
            && !array_key_exists($key, $this->otherProperties)
            && !method_exists($this, $getter)
        ) {
            throw new KiotVietException("Property $key not exist on " . self::class);
        }

        return $this->$getter() ?? ($this->otherProperties[$key] ?? null);
    }
}
