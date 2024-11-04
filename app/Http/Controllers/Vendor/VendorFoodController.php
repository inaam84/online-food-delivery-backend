<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Resources\Vendor\VendorFoodResource;
use App\Interfaces\VendorFoodRepositoryInterface;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class VendorFoodController extends Controller
{
    private VendorFoodRepositoryInterface $vendorFoodRepository;

    public function __construct(VendorFoodRepositoryInterface $vendorFoodRepository)
    {
        $this->vendorFoodRepository = $vendorFoodRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('index', Food::class);

        if (isVendor(auth()->user())) {
            return VendorFoodResource::collection(
                auth()->user()->foods
            );
        }

        return VendorFoodResource::collection(
            $this->vendorFoodRepository->getAllFoods(array_filter($request->all()))
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Food::class);

        // sanity check driver can only provide his/her own id
        if (isVendor(auth()->user()) && auth()->user()->id != $request->vendor_id) {
            return jsonResponse(['message' => __('Bad Request')], Response::HTTP_BAD_REQUEST);
        }

        $food = $this->vendorFoodRepository->createFood($request->validated());

        return response()->json(new VendorFoodResource($food));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $food = $this->vendorFoodRepository->getFoodById($id);

        return response()->json(new VendorFoodResource($food));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $food = $this->vendorFoodRepository->getFoodById($id);
        if (is_null($food)) {
            return jsonResponse(['message' => __('Not Found')], Response::HTTP_NOT_FOUND);
        }

        Gate::authorize('update', $food);

        if ($request->vendor_id != $food->vendor_id) {
            return jsonResponse(['message' => __('Bad Request')], Response::HTTP_BAD_REQUEST);
        }

        $food = $this->vendorFoodRepository->updateFood($id, $request->validated());

        return response()->json(new VendorFoodResource($food));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $food = $this->vendorFoodRepository->getFoodById($id);
        if (is_null($food)) {
            return jsonResponse(['message' => __('Record not found.')], Response::HTTP_NOT_FOUND);
        }

        // sanity check driver can only provide his/her own id
        if (isVendor(auth()->user()) && auth()->user()->id != $food->vendor_id) {
            return jsonResponse(['message' => __('Bad Request')], Response::HTTP_BAD_REQUEST);
        }

        $food->delete();

        return jsonResponse(['message' => 'Food information deleted.']);
    }
}
