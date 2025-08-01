<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Models\Author;
use App\Repositories\BookRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @OA\Tag(
 *     name="Author Books",
 *     description="API Endpoints for Books by Author"
 * )
 */
class AuthorBooksController extends Controller
{
    /**
     * @var BookRepositoryInterface
     */
    protected $bookRepository;

    /**
     * AuthorBooksController constructor.
     *
     * @param BookRepositoryInterface $bookRepository
     */
    public function __construct(BookRepositoryInterface $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    /**
     * Display a listing of books by a specific author.
     *
     * @OA\Get(
     *     path="/api/authors/{author}/books",
     *     operationId="getBooksByAuthor",
     *     tags={"Author Books"},
     *     summary="Get list of books by author",
     *     description="Returns list of books by a specific author",
     *     @OA\Parameter(
     *         name="author",
     *         in="path",
     *         description="Author ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/BookResource")
     *             ),
     *             @OA\Property(
     *                 property="links",
     *                 type="object",
     *                 @OA\Property(property="first", type="string"),
     *                 @OA\Property(property="last", type="string"),
     *                 @OA\Property(property="prev", type="string", nullable=true),
     *                 @OA\Property(property="next", type="string", nullable=true)
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer"),
     *                 @OA\Property(property="from", type="integer"),
     *                 @OA\Property(property="last_page", type="integer"),
     *                 @OA\Property(property="path", type="string"),
     *                 @OA\Property(property="per_page", type="integer"),
     *                 @OA\Property(property="to", type="integer"),
     *                 @OA\Property(property="total", type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Author not found"
     *     )
     * )
     *
     * @param Author $author
     * @return AnonymousResourceCollection
     */
    public function index(Author $author)
    {
        $books = $this->bookRepository->getBooksByAuthor($author->id);

        return BookResource::collection($books);
    }
}
