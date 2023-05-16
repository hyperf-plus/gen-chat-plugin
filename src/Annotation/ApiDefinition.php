<?php


namespace HPlus\ChatPlugins\Annotation;

use Attribute;
use Hyperf\Di\Annotation\AbstractAnnotation;
/**
 * @Annotation
 * @Target({"ALL"})
 */
#[Attribute(Attribute::TARGET_ALL)]
class ApiDefinition extends AbstractAnnotation
{
    public $name;
    public $properties;
}