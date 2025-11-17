<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    // Define properti
    public $status;
    public $message;

    /**
     * __construct
     *
     * @param  mixed $status
     * @param  mixed $message
     * @param  mixed $resource
     * @return void
     */
    public function __construct($status, $message, $resource)
    {
        parent::__construct($resource);
        $this->status  = $status;
        $this->message = $message;
    }

    /**
     * toArray
     *
     * @param  mixed $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        $data = [];

        // Cek apakah resource memiliki "table1"
        if (isset($this->resource['table1'])) {
            $data['table1'] = $this->resource['table1'];
        }

        // Cek apakah resource memiliki "table2"
        if (isset($this->resource['table2'])) {
            $data['table2'] = $this->resource['table2'];
        }

        // Cek apakah resource memiliki "table2"
        if (isset($this->resource['table3'])) {
            $data['table2'] = $this->resource['table3'];
        }

        return [
            'success'   => $this->status,
            'message'   => $this->message,
            'data'      => $this->resource
        ];
    }
}
