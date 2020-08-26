<?php
/**
 * Service utils
 *
 * @package   App\Utils
 * @version   0.0.1
 * @author    Rayzen-dev <rayzen.dev@gmail.com>
 * @copyright no copyrights
 */

namespace App\Utils;


use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ServiceContainer
 */
class ServiceContainer
{

    /**
     * Container interface
     *
     * @var ContainerInterface
     */
    private $container;


    /**
     * ServiceContainer constructor.
     *
     * @param ContainerInterface $container Container injection dependency.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

    }//end __construct()


    /**
     * Get parameter by name from paramters.yml
     *
     * @param string $parameter Parameter name.
     *
     * @return mixed
     */
    public function getParameter(string $parameter)
    {
        return $this->container->getParameter($parameter);

    }//end getParameter()


    /**
     * Get the translated sentence
     *
     * @param string $identifier   Key of sentence.
     * @param array  $stringsArray None.
     *
     * @return string
     */
    public function translate(string $identifier, array $stringsArray=[]): string
    {
        return $this->container->get('translator')->trans($identifier, $stringsArray);

    }//end translate()


}//end class
