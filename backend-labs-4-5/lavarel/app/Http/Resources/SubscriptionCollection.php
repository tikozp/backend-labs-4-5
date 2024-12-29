<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SubscriptionCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($subscription) {
                return [
                    'id' => $subscription->id,
                    'subscriber_id' => $subscription->subscriber_id,
                    'subscriber' => new SubscriberResource($subscription->whenLoaded('subscriber')),
                    'start_date' => $subscription->start_date->format('Y-m-d'),
                    'end_date' => $subscription->end_date->format('Y-m-d'),
                    'status' => $subscription->status,
                    'subscription_type' => $subscription->subscription_type,
                    'price' => $subscription->price,
                    'created_at' => $subscription->created_at,
                    'updated_at' => $subscription->updated_at
                ];
            }),
            'meta' => [
                'current_page' => $this->currentPage(),
                'from' => $this->firstItem(),
                'last_page' => $this->lastPage(),
                'per_page' => $this->perPage(),
                'to' => $this->lastItem(),
                'total' => $this->total(),
            ],
            'links' => [
                'first' => $this->url(1),
                'last' => $this->url($this->lastPage()),
                'prev' => $this->previousPageUrl(),
                'next' => $this->nextPageUrl()
            ]
        ];
    }

    public function withResponse($request, $response)
    {
        $jsonResponse = json_decode($response->getContent(), true);
        $response->setContent(json_encode($jsonResponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}