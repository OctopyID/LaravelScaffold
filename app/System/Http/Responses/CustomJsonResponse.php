<?php

namespace App\System\Http\Responses;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CustomJsonResponse extends JsonResponse
{
    protected array $temps = [];

    /**
     * @return $this
     */
    public function text(string $text, array $replace = []) : static
    {
        $this->temps = array_merge($this->temps, [
            'message' => __($text, $replace),
        ]);

        $this->setData($this->temps);

        return $this;
    }

    /**
     * @return $this
     */
    public function data(Arrayable|JsonResource|ResourceCollection|array $data) : static
    {
        if ($data instanceof JsonResource || $data instanceof ResourceCollection) {
            $data = $data->jsonSerialize();
        }

        $this->temps = array_merge($this->temps, [
            'data' => $data,
        ]);

        $this->setData($this->temps);

        return $this;
    }

    /**
     * @return $this
     */
    public function code(int $code) : static
    {
        $this->setStatusCode($code);

        return $this;
    }
}
