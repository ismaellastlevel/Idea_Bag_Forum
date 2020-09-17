<?php


namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class InstanceOfExtension extends AbstractExtension
{

    public function getFilters()
    {
        return [
            new TwigFilter('instanceOf', [$this, 'instanceOf']),
        ];
    }//end getFilters()


    /**
     * Check the instance of a variable
     *
     * @param mixed  $var Var to test.
     * @param string $instance Instance to check.
     *
     * @return bool
     */
    public function instanceOf($var, string $instance): bool
    {
        if (!is_object($var)) {
            return false;
        }

        $reflexionClass = new \ReflectionClass($instance);
        return $reflexionClass->isInstance($var);
    }//end instanceOf()

}