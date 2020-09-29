<?php
/**
 * Common manager functions
 *
 * @package   App\Manager
 * @version   0.0.1
 * @author    Rayzen-dev <rayzen.dev@gmail.com>
 * @copyright no copyrights
 */

namespace App\Manager;


use App\Utils\ServiceContainer;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class BaseManager
 */
class BaseManager
{

    /**
     * Service container
     *
     * @var ServiceContainer $sc
     */
    protected $sc;

    /**
     * Entity manager
     *
     * @var EntityManager;
     */
    protected $em;


    /**
     * BaseManager constructor.
     *
     * @param EntityManagerInterface $em EntityManager instance.
     * @param ServiceContainer       $sc ServiceContainer instance.
     */
    public function __construct(EntityManagerInterface $em, ServiceContainer $sc)
    {
        $this->sc = $sc;
        $this->em = $em;
    }//end __construct()


    /**
     * Persist entity in memory and choose to save into database or no.
     *
     * @param mixed   $entity    Entity to save.
     * @param boolean $withFlush Boolean for insert or update entity in the database.
     *
     * @return void
     *
     * @throws \Doctrine\ORM\OptimisticLockException Throw error.
     */
    public function persistEntity($entity, bool $withFlush=false)
    {
        $this->em->persist($entity);
        if (true === $withFlush) {
            $this->em->flush();
        }

    }//end persistEntity()


    /**
     * Remove entity from database
     *
     * @param mixed   $entity    Entity to delete.
     * @param boolean $withFlush Remove immediatly.
     *
     * @return void
     *
     * @throws \Doctrine\ORM\OptimisticLockException Throw error.
     */
    public function removeEntity($entity, bool $withFlush=false)
    {
        $this->em->remove($entity);
        if (true === $withFlush) {
            $this->em->flush();
        }

    }//end removeEntity()


    /**
     * Save into database
     *
     * @throws \Doctrine\ORM\OptimisticLockException Throw error.
     * @return void
     */
    public function flush()
    {
        $this->em->flush();

    }//end flush()


    /**
     * Clear entities stored in memory.
     *
     * @return void
     */
    public function clear()
    {
        $this->em->clear();

    }//end clear()


    /**
     * Fetch one entity from the database by id
     *
     * @param object $entity
     * @param int $id
     *
     * @return object|null
     */
    public function fetchOneEntityById($entityName, int $id)
    {
        return $this->em->getRepository($entityName)->findOneBy(['id' => $id]);
    }


    /**
     * Render not found error
     *
     * @param string|null $message Message of the error.
     * @param array       $options Options for the message.
     *
     * @throws NotFoundHttpException Throw the error.
     * @return void
     */
    public function renderNotFound(string $message=null, array $options=[])
    {
        $message = (null !== $message) ? $message : 'exception.page_not_found';
        throw new NotFoundHttpException(
            $this->sc->translate(
                $message,
                $options
            )
        );

    }//end renderNotFound()


    /**
     * Render access denied error
     *
     * @param string|null $message Message of the error.
     * @param array       $options Options for the error.
     *
     * @throws AccessDeniedHttpException Throw the error.
     * @return void
     */
    public function renderAccessDenied(string $message=null, array $options=[])
    {
        $message = (null !== $message) ? $message : 'exception.access_denied';
        throw new AccessDeniedHttpException(
            $this->sc->translate(
                $message,
                $options
            )
        );

    }//end renderAccessDenied()


    /**
     * Render bad request error
     *
     * @param string|null $message The message to output.
     * @param array       $options Options for the message.
     *
     * @throws BadRequestHttpException Throw the error.
     * @return void
     */
    public function renderBadRequest(string $message=null, array $options=[])
    {
        $message = (null !== $message) ? $message : 'exception.bad_request';
        throw new BadRequestHttpException(
            $this->sc->translate(
                $message,
                $options
            )
        );

    }//end renderBadRequest()


    /**
     * Render a internal error
     *
     * @param string|null $message Message of error.
     * @param array       $options Options for the message.
     *
     * @throws \Exception Throw the error.
     * @return void
     */
    public function renderInternalException(string $message=null, array $options=[])
    {
        $message = (null !== $message) ? $message : 'exception.internal_server';
        throw new \Exception(
            $this->sc->translate(
                $message,
                $options
            )
        );

    }//end renderInternalException()


    /**
     * @param EntityManager $em
     */
    public function setEm(EntityManager $em): void
    {
        $this->em = $em;
    }


}//end class
