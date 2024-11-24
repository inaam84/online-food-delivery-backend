<?php

namespace App\Http\Controllers\Food;

use App\Http\Controllers\Controller;
use App\Http\Resources\Food\FoodResource;
use App\Interfaces\FoodRepositoryInterface;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class FoodController extends Controller
{
    private FoodRepositoryInterface $foodRepository;

    public function __construct(FoodRepositoryInterface $foodRepository)
    {
        $this->foodRepository = $foodRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (isVendor(auth()->user())) {
            return FoodResource::collection(
                auth()->user()->foods
            );
        }

        return FoodResource::collection(
            $this->foodRepository->getAllFoods(array_filter($request->all()))
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Food::class);

        $data = [
            'vendor_id' => $request->vendor_id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'status' => $request->status,
        ];
        $food = $this->foodRepository->createFood($data);

        return response()->json(new FoodResource($food));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $food = $this->foodRepository->getFoodById($id);

        return response()->json(new FoodResource($food));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $food = $this->foodRepository->getFoodById($id);
        if (is_null($food)) {
            return jsonResponse(['message' => __('Not Found.')], Response::HTTP_NOT_FOUND);
        }

        Gate::authorize('update', $food);

        if ($request->vendor_id != $food->vendor_id) {
            return jsonResponse(['message' => __('Bad Request')], Response::HTTP_BAD_REQUEST);
        }

        $food = $this->foodRepository->updateFood($id, [
            'category_id' => $request->input('category_id', $food->category_id),
            'name' => $request->input('name', $food->name),
            'description' => $request->input('description', $food->description),
            'price' => $request->input('price', $food->price),
            'status' => $request->input('status', $food->status),
        ]);

        return response()->json(new FoodResource($food));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $food = $this->foodRepository->getFoodById($id);
        if (is_null($food)) {
            return jsonResponse(['message' => __('Record not found.')], Response::HTTP_NOT_FOUND);
        }

        // sanity check driver can only provide his/her own id
        if (isVendor(auth()->user()) && auth()->user()->id != $food->vendor_id) {
            return jsonResponse(['message' => __('Bad Request')], Response::HTTP_BAD_REQUEST);
        }

        $deleted = $this->foodRepository->deleteFood($id);

        return jsonResponse(['message' => $deleted ? 'Food information deleted.' : 'Food information cannot be deleted.'.$deleted]);
    }
}
