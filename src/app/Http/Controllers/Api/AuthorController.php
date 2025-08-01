<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthorResource;
use App\Repositories\AuthorRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @OA\Tag(
 *     name="Authors",
 *     description="API Endpoints for Authors"
 * )
 */
class AuthorController extends Controller
{
    /**
     * @var AuthorRepositoryInterface
     */
    protected $authorRepository;

    /**
     * AuthorController constructor.
     *
     * @param AuthorRepositoryInterface $authorRepository
     */
    public function __construct(AuthorRepositoryInterface $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    /**
     * Display a listing of authors with optional search filters.
     *
     * @OA\Get(
     *     path="/api/authors",
     *     operationId="getAuthorsList",
     *     tags={"Authors"},
     *     summary="Get list of authors",
     *     description="Returns list of authors with book count and optional search filters",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Search by author name",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/AuthorResource")
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
        $name = $request->input('name');

        $authors = $this->authorRepository->searchAuthors($name);

        return AuthorResource::collection($authors);
    }
}
