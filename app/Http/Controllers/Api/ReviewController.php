<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Property;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function submitReview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rental_booking_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'comment_ar' => 'nullable|string',
            'comment_en' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $user = $request->user();
            $booking = Booking::where('user_id', $user->id)->find($request->rental_booking_id);

            if (!$booking) {
                return response()->json(['message' => 'Booking not found'], 404);
            }

            if ($booking->status !== 'completed') {
                return response()->json(['message' => 'You can only review completed bookings'], 400);
            }

            $existingReview = Review::where('booking_id', $booking->id)->first();
            if ($existingReview) {
                return response()->json(['message' => 'You have already reviewed this booking'], 400);
            }

            $review = Review::create([
                'booking_id' => $booking->id,
                'property_id' => $booking->property_id,
                'user_id' => $user->id,
                'rating' => $request->rating,
                'comment_ar' => $request->comment_ar,
                'comment_en' => $request->comment_en,
                'status' => true,
            ]);

            $property = Property::find($booking->property_id);
            if ($property) {
                $totalRating = Review::where('property_id', $property->id)->where('status', true)->sum('rating');
                $ratingCount = Review::where('property_id', $property->id)->where('status', true)->count();

                $property->update([
                    'avg_rating' => $ratingCount > 0 ? ($totalRating / $ratingCount) : 0.00,
                    'rating_count' => $ratingCount,
                ]);
            }

            return response()->json(['message' => 'Review submitted successfully', 'review' => $review], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
