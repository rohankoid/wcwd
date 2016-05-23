<?php

namespace Dance\Model;

use Doctrine\DBAL\Connection;
use Dance\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/**
 * User Model
 */
class UserModel implements ModelInterface, UserProviderInterface
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $db;

    /**
     * @var \Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder
     */
    protected $encoder;

    public function __construct(Connection $db, $encoder)
    {
        $this->db = $db;
        $this->encoder = $encoder;
    }

    /**
     * Saves the user to the database.
     *
     * @param \Dance\Entity\User $user
     */
    public function save($user)
    {
        $userData = array(
            'username' => $user->getUsername(),
            'mail' => $user->getMail(),
            'role' => $user->getRole(),
            'token' => $user->getToken(),
        );
        // If the password was changed, re-encrypt it.
        if (strlen($user->getPassword()) != 88) {
            $userData['salt'] = uniqid(mt_rand());
            $userData['password'] = $this->getEncodedPassword($user->getPassword(), $userData['salt']);
        }

        if ($user->getIdUser()) {
            $this->db->update('wcwd_user', $userData, array('iduser' => $user->getIduser()));
        } else {
            // The user is new, note the creation timestamp.
            $now = new DateTime();
            $created_at = $now->format('Y-m-d H:i:s');
            $userData['created_at'] = $created_at;
            $this->db->insert('wcwd_user', $userData);
            // Get the id of the newly created user and set it on the entity.
            $id = $this->db->lastInsertId();
            $user->setIdUser($id);

        }
    }

    /**
     * Deletes the user.
     *
     * @param integer $id
     */
    public function delete($id)
    {
        return $this->db->delete('wcwd_user', array('iduser' => $id));
    }

    /**
     * Returns the total number of users.
     *
     * @return integer The total number of users.
     */
    public function getCount() {
        return $this->db->fetchColumn('SELECT COUNT(iduser) FROM wcwd_user');
    }

    /**
     * Returns a user matching the supplied id.
     *
     * @param integer $id
     *
     * @return \Dance\Entity\User|false An entity object if found, false otherwise.
     */
    public function find($id)
    {
        $userData = $this->db->fetchAssoc('SELECT * FROM wcwd_user WHERE iduser = ?', array($id));
        return $userData ? $this->buildUser($userData) : FALSE;
    }

    /**
     * Returns a collection of users.
     *
     * @param integer $limit
     *   The number of users to return.
     * @param integer $offset
     *   The number of users to skip.
     * @param array $orderBy
     *   Optionally, the order by info, in the $column => $direction format.
     *
     * @return array A collection of users, keyed by user id.
     */
    public function findAll($limit, $offset = 0, $orderBy = array())
    {
        // Provide a default orderBy.
        if (!$orderBy) {
            $orderBy = array('username' => 'ASC');
        }

        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder
            ->select('u.*')
            ->from('wcwd_user', 'u')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->orderBy('u.' . key($orderBy), current($orderBy));
        $statement = $queryBuilder->execute();
        $usersData = $statement->fetchAll();

        $users = array();
        foreach ($usersData as $userData) {
            $userId = $userData['iduser'];
            $users[$userId] = $this->buildUser($userData);
        }

        return $users;
    }

    /**
     * {@inheritDoc}
     */
    public function loadUserByUsername($username)
    {
        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder
            ->select('u.*')
            ->from('wcwd_user', 'u')
            ->where('u.username = :username OR u.mail = :mail')
            ->setParameter('username', $username)
            ->setParameter('mail', $username)
            ->setMaxResults(1);
        $statement = $queryBuilder->execute();
        $usersData = $statement->fetchAll();
        if (empty($usersData)) {
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
        }

        $user = $this->buildUser($usersData[0]);
        return $user;
    }

    /**
     * {@inheritDoc}
     */
    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $class));
        }

        $id = $user->getIdUser();
        $refreshedUser = $this->find($id);
        if (false === $refreshedUser) {
            throw new UsernameNotFoundException(sprintf('User with id %s not found', json_encode($id)));
        }

        return $refreshedUser;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        return 'Dance\Entity\User' === $class;
    }

    /**
     * Instantiates a user entity and sets its properties using db data.
     *
     * @param array $userData
     *   The array of db data.
     *
     * @return \Dance\Entity\User
     */
    protected function buildUser($userData)
    {
        $user = new User();
        $user->setIdUser($userData['iduser']);
        $user->setUsername($userData['username']);
        $user->setSalt($userData['salt']);
        $user->setPassword($userData['password']);
        $user->setMail($userData['mail']);
        $user->setRole($userData['role']);
        $user->setToken($userData['token']);
        $createdAt = $userData['created_at'];
        $user->setCreatedAt($createdAt);
        return $user;
    }

    /**
     * @param $salt String
     * @param $password plain text password
     *
     * @return String encoded password
     */
    public function getEncodedPassword($password, $salt)
    {
        return $this->encoder->encodePassword($password, $salt);
    }
}
