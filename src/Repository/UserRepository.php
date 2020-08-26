<?php
/**
 * User repository
 *
 * @package   src/Repository
 * @version   0.0.1
 * @author    Adrien Colonna
 * @copyright no copyrights
 */

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserRepository
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{


    /**
     * UserRepository constructor.
     *
     * @param ManagerRegistry $registry Manager registry.
     *
     * @return void
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);

    }//end __construct()


    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     *
     * @param UserInterface $user               User.
     * @param string        $newEncodedPassword String.
     *
     * @return void
     * @throws ORMException|OptimisticLockException|UnsupportedUserException OrmException throw.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (($user instanceof User) === false) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();

    }//end upgradePassword()


    /**
     * Function load user with email or username
     *
     * @param string $usernameOrEmail Get user email or username.
     *
     * @return integer|mixed|string|null
     *
     * @throws NonUniqueResultException Throw non unique.
     */
    public function loadUserByUsername(string $usernameOrEmail)
    {
        return $this->createQueryBuilder('user')->where('user.username = :username OR user.email = :email')->setParameter('username', $usernameOrEmail)->setParameter('email', $usernameOrEmail)->getQuery()->getOneOrNullResult();

    }//end loadUserByUsername()


}//end class
