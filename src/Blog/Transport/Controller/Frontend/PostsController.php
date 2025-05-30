<?php

declare(strict_types=1);

namespace App\Blog\Transport\Controller\Frontend;

use App\Blog\Application\ApiProxy\UserProxy;
use App\Blog\Domain\Repository\Interfaces\PostRepositoryInterface;
use App\General\Domain\Utils\JSON;
use Closure;
use Doctrine\ORM\Exception\NotSupported;
use Exception;
use JsonException;
use OpenApi\Attributes as OA;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * @package App\Blog
 */
#[AsController]
#[OA\Tag(name: 'Blog')]
readonly class PostsController
{
    public function __construct(
        private SerializerInterface $serializer,
        private CacheInterface $cache,
        private PostRepositoryInterface $postRepository,
        private UserProxy $userProxy
    ) {
    }

    /**
     * Get current user blog data, accessible only for 'IS_AUTHENTICATED_FULLY' users
     *
     * @throws InvalidArgumentException
     * @throws JsonException
     * @return JsonResponse
     */
    #[Route(path: '/public/post', name: 'public_post_index', methods: [Request::METHOD_GET])]
    #[Cache(smaxage: 10)]
    public function __invoke(): JsonResponse
    {
        $cacheKey = 'all_public_post';
        $blogs = $this->cache->get($cacheKey, fn (ItemInterface $item) => $this->getClosure()($item));
        $output = JSON::decode(
            $this->serializer->serialize(
                $blogs,
                'json',
                [
                    'groups' => 'Post',
                ]
            ),
            true,
        );
        return new JsonResponse($output);
    }

    /**
     *
     * @return Closure
     */
    private function getClosure(): Closure
    {
        return function (ItemInterface $item): array {
            $item->expiresAfter(3600);

            return $this->getFormattedPosts();
        };
    }

    /**
     * @throws NotSupported
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @return array
     */
    private function getFormattedPosts(): array
    {
        $users = $this->userProxy->getUsers();

        $usersById = [];
        foreach ($users as $user) {
            $usersById[$user['id']] = $user;
        }

        $posts = $this->getPosts();
        $output = [];

        foreach ($posts as $post) {
            $authorId = $post->getAuthor()->toString();

            $postData = [
                'id' => $post->getId(),
                'title' => $post->getTitle(),
                'summary' => $post->getSummary(),
                'content' => $post->getContent(),
                'tags' => $post->getTags(),
                'medias' => $post->getMedias(),
                'likes' => $post->getLikes(),
                'blog' => [
                    'title' => $post->getBlog()->getTitle(),
                    'blogSubtitle' => $post->getBlog()->getBlogSubtitle(),
                ],
                'user' => $usersById[$authorId] ?? null,
                'comments' => [],
            ];

            foreach ($post->getComments() as $comment) {
                $commentAuthorId = $comment->getAuthor()->toString();

                $postData['comments'][] = [
                    'id' => $comment->getId(),
                    'content' => $comment->getContent(),
                    'publishedAt' => $comment->getPublishedAt()?->format(DATE_ATOM),
                    'user' => $usersById[$commentAuthorId] ?? null,
                ];
            }

            $output[] = $postData;
        }

        return $output;
    }


    /**
     * @throws NotSupported
     * @return array
     */
    private function getPosts(): array
    {
        return $this->postRepository->findAll();
    }
}
