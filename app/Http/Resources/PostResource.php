<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{

    // define property
    public $status;
    public $message;
    public $resource;

    /**
     * Create a new resource instance.
     *
     * @param mixed $resource
     * @param string $message
     * @param string $status
     */

    public function __construct($resource, string $message, string $status)
    {
        parent::__construct($resource);
        $this->message = $message;
        $this->status = $status;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->resource,
        ];
    }
}
