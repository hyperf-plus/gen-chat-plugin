<?php
declare(strict_types=1);
namespace HPlus\ChatPlugins;

use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\Di\Annotation\AnnotationReader;
use Hyperf\Di\ReflectionManager;

class ApiAnnotation
{
    public static function methodMetadata($className, $methodName)
    {
        $reflectMethod = ReflectionManager::reflectMethod($className, $methodName);
        $reader = new AnnotationReader(config('annotations.scan.ignore_annotations', []));
        return $reader->getMethodAnnotations($reflectMethod);
    }

    public static function classMetadata($className) {
        return AnnotationCollector::list()[$className]['_c'] ?? [];
    }
}
