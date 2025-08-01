<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Repositories\BookRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @OA\Tag(
 *     name="Books",
 *     description="API Endpoints for Books"
 * )
 */
class BookController extends Controller
{
    /**
     * @var BookRepositoryInterface
     */
    protected $bookRepository;

    /**
     * BookController constructor.
     *
     * @param BookRepositoryInterface $bookRepository
     */
    public function __construct(BookRepositoryInterface $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    /**
     * Display a listing of books with optional search filters.
     *
     * @OA\Get(
     *     path="/api/books",
     *     operationId="getBooksList",
     *     tags={"Books"},
     *     summary="Get list of books",
     *     description="Returns list of books with optional search filters",
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="Search by book title",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         description="Search by book description",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="author",
     *         in="query",
     *         description="Search by author name",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="author_id",
     *         in="query",
     *         description="Search by author ID",
     *         required=false,
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
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $title = $request->input('title');
        $description = $request->input('description');
        $author = $request->input('author');
        $authorId = $request->input('author_id');

        $books = $this->bookRepository->searchBooks($title, $description, $author, $authorId);

        return BookResource::collection($books);
    }
}
