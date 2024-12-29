<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'subscriber_id' => $this->subscriber_id,
            'subscriber' => new SubscriberResource($this->whenLoaded('subscriber')),
            'start_date' => $this->start_date->format('Y-m-d'),
            'end_date' => $this->end_date->format('Y-m-d'),
            'status' => $this->status,
            'subscription_type' => $this->subscription_type,
            'price' => $this->price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}