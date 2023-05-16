<?php
declare(strict_types=1);
namespace HPlus\ChatPlugins\Annotation;

use Attribute;
use Hyperf\Di\Annotation\AbstractAnnotation;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ApiVersion extends AbstractAnnotation
{
    public $version;
}
