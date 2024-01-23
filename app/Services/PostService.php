<?php

namespace App\Services;

use App\Entities\Post;
use DateTimeImmutable;
use Doctrine\DBAL\Exception;
use Pmguru\Framework\Dbal\EntityService;
use Pmguru\Framework\Http\Exceptions\NotFoundException;

class PostService
{
    
    public function __construct(
        private readonly EntityService $service,
    ) {
    }
    
    /**
     * @throws Exception
     */
    public function save(Post $post)
    : Post {
        $queryBuilder = $this->service->getConnection()->createQueryBuilder();
        $queryBuilder
            ->insert('posts')
            ->values([
                'title'      => ':title',
                'body'       => ':body',
                'created_at' => ':created_at',
            ])
            ->setParameters([
                'title'      => $post->getTitle(),
                'body'       => $post->getBody(),
                'created_at' => $post->getCreatedAt()->format('Y-m-d H:i:s'),
            ])
            ->executeQuery();
        
        $id = $this->service->save($post);
        $post->setId($id);
        
        return $post;
    }
    
    /**
     * @throws Exception
     * @throws \Exception
     */
    public function find(int $id)
    : ?Post {
        $queryBuilder = $this->service->getConnection()->createQueryBuilder();
        
        $result = $queryBuilder
            ->select('*')
            ->from('posts')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->executeQuery();
        
        $post = $result->fetchAssociative();
        
        if (!$post) {
            return null;
        }
        
        return Post::create(
            title: $post['title'],
            body: $post['body'],
            id: $post['id'],
            createdAt: new DateTimeImmutable($post['created_at']),
        );
    }
    
    /**
     * @throws NotFoundException
     * @throws Exception
     */
    public function findOrFail(int $id)
    : Post {
        $post = $this->find($id);
        
        if (is_null($post)) {
            throw new NotFoundException("Post with id = $id not found");
        }
        
        return $post;
    }
    
}