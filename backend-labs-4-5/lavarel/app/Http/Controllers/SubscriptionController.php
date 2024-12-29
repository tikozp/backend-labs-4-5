<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Http\Resources\SubscriptionResource;
use App\Http\Resources\SubscriptionCollection;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;

class SubscriptionController extends Controller
{
    use AuthorizesRequests, ValidatesRequests, DispatchesJobs;

    public function __construct()
    {
        $this->middleware(\App\Http\Middleware\KeycloakMiddleware::class);
    }

    public function index(Request $request)
    {
        try {
            $subscriptions = Subscription::with('subscriber')->paginate(10);
            return new SubscriptionCollection($subscriptions);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error fetching subscriptions',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'subscriber_id' => 'required|exists:subscribers,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'status' => 'required|in:active,expired,cancelled',
                'subscription_type' => 'required|string',
                'price' => 'required|numeric|min:0'
            ]);

            $subscription = Subscription::create($validated);
            return new SubscriptionResource($subscription);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error creating subscription',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => [
                    'request_data' => $request->all()
                ]
            ], 500);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $subscription = Subscription::findOrFail($id);
            return new SubscriptionResource($subscription);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error fetching subscription',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $subscription = Subscription::findOrFail($id);

            $validated = $request->validate([
                'subscriber_id' => 'exists:subscribers,id',
                'start_date' => 'date',
                'end_date' => 'date|after:start_date',
                'status' => 'in:active,expired,cancelled',
                'subscription_type' => 'string',
                'price' => 'numeric|min:0'
            ]);

            $subscription->update($validated);
            return new SubscriptionResource($subscription);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error updating subscription',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => [
                    'request_data' => $request->all(),
                    'subscription_id' => $id
                ]
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $subscription = Subscription::findOrFail($id);
            $subscription->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Subscription deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error deleting subscription',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => [
                    'subscription_id' => $id
                ]
            ], 500);
        }
    }
}