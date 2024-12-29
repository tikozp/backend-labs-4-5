<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Http\Resources\SubscriberResource;
use App\Http\Resources\SubscriberCollection;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;

class SubscriberController extends Controller 
{
    use AuthorizesRequests, ValidatesRequests, DispatchesJobs;

    public function __construct()
    {
        $this->middleware(\App\Http\Middleware\KeycloakMiddleware::class);
    }

    public function index(Request $request)
    {
        try {
            $subscribers = Subscriber::paginate(10);
            return new SubscriberCollection($subscribers);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error fetching subscribers',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $subscriber = Subscriber::findOrFail($id);
            return new SubscriberResource($subscriber);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error fetching subscriber',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            // Convert is_active to boolean before validation
            if ($request->has('is_active')) {
                $request->merge([
                    'is_active' => filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN)
                ]);
            }

            $validated = $request->validate([
                'email' => 'required|email|unique:subscribers',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'is_active' => 'boolean'
            ]);
   
            $subscriber = Subscriber::create($validated);
            return new SubscriberResource($subscriber);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error creating subscriber',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => [
                    'request_data' => $request->all(),
                    'is_active_type' => gettype($request->is_active),
                    'is_active_value' => $request->is_active
                ]
            ], 500);
        }
    }

    public function update(Request $request, Subscriber $subscriber)
    {
        try {
            // Convert is_active to boolean before validation
            if ($request->has('is_active')) {
                $request->merge([
                    'is_active' => filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN)
                ]);
            }

            $validated = $request->validate([
                'email' => 'email|unique:subscribers,email,' . $subscriber->id,
                'first_name' => 'string|max:255',
                'last_name' => 'string|max:255',
                'is_active' => 'boolean'
            ]);

            $subscriber->update($validated);
            return new SubscriberResource($subscriber);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error updating subscriber',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

   public function destroy(Subscriber $subscriber)
   {
       try {
           $subscriber->delete();
           return response()->json([
               'status' => 'success',
               'message' => 'Subscriber deleted successfully'
           ]);
       } catch (\Exception $e) {
           return response()->json([
               'status' => 'error',
               'message' => 'Error deleting subscriber',
               'error' => $e->getMessage(),
               'trace' => $e->getTraceAsString()
           ], 500);
       }
   }
}