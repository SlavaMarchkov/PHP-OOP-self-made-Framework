<?php

declare(strict_types=1);

namespace App\Services;

use App\Entities\User;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Pmguru\Framework\Authentication\AuthUserInterface;
use Pmguru\Framework\Authentication\UserServiceInterface;

final class UserService implements UserServiceInterface
{
    
    public function __construct(
        private readonly Connection $connection,
    ) {
    }
    
    /**
     * @throws Exception
     */
    public function save(User $user)
    : User {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->insert('users')
            ->values([
                'name'       => ':name',
                'email'      => ':email',
                'password'   => ':password',
                'created_at' => ':created_at',
            ])
            ->setParameters([
                'name'       => $user->getName(),
                'email'      => $user->getEmail(),
                'password'   => $user->getPassword(),
                'created_at' => $user->getCreatedAt()->format('Y-m-d H:i:s'),
            ])
            ->executeQuery();
        
        $id = (int)$this->connection->lastInsertId();
        $user->setId($id);
        
        return $user;
    }
    
    /**
     * @param string $email
     * @return AuthUserInterface|null
     * @throws Exception
     * @throws \Exception
     */
    public function findByEmail(string $email)
    : ?AuthUserInterface {
        $queryBuilder = $this->connection->createQueryBuilder();
        $result = $queryBuilder->select('*')
            ->from('users')
            ->where('email = :email')
            ->setParameter('email', $email)
            ->executeQuery();
        
        $user = $result->fetchAssociative();
        
        if (!$user) {
            return null;
        }
        
        return User::create(
            email: $user['email'],
            password: $user['password'],
            createdAt: new DateTimeImmutable($user['created_at']),
            name: $user['name'],
            id: $user['id']
        );
    }
    
}