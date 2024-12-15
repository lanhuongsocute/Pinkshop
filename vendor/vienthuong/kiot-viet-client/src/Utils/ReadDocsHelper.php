<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient\Utils;

use VienThuong\KiotVietClient\Model\BaseModel;

class ReadDocsHelper
{
    public static $ignoredTypes = [
        'bool',
        'boolean',
        'string',
        'int',
        'integer',
        'float',
        'double',
        'array',
        'object',
        'callable',
        'resource',
        'mixed',
        'iterable',
    ];

    public static function detectPropertyType(string $class, string $propertyName, ?string $nameSpace = null) : string
    {
        $property = new \ReflectionProperty($class, $propertyName);
        $propertyType = null;

        if (phpversion() >= '7.4') {
            $propertyType = (string) $property->getType();
        }

        if (empty($propertyType)) {
            if (preg_match('/@var\s+([^\s]+)/', $property->getDocComment(), $matches)) {
                list(, $propertyType) = $matches;

                if (in_array($propertyType, self::$ignoredTypes)) {
                    return $propertyType;
                }

                if (class_exists($propertyType)) {
                    return $propertyType;
                }

                $nameSpace = $nameSpace ?? $class;
                $nameSpace = (new \ReflectionClass($nameSpace))->getNamespaceName();

                if (class_exists($modelType = $nameSpace . '\\' . $propertyType)) {
                    return $modelType;
                }

                if (class_exists($collectionType = str_replace('Model', 'Collection', $modelType))) {
                    return $collectionType;
                }
            }
        }

        return $propertyType;
    }
}
