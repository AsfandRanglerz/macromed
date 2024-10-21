<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WhishList;
use Illuminate\Http\Request;

class WhishlistController extends Controller
{
    public function getWhishList($userId)
    {
        try {
            $wishListItems = WhishList::where('user_id', $userId)
                ->with('product') // Assuming there is a product relationship
                ->get();

            // Check if the wishlist is empty
            if ($wishListItems->isEmpty()) {
                return response()->json([
                    'data' => []
                ], 200);
            } else {
                return response()->json([
                    'status' => 'success',
                    'data' => $wishListItems
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching whishlist data: ' . $e->getMessage()
            ], 500);
        }
    }
}
