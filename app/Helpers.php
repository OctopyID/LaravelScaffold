<?php

use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\App;

if (! function_exists('path_to_class')) {
    /**
     * Convert path to class name with namespace.
     */
    function path_to_class(SplFileInfo|string $file) : string
    {
        if ($file instanceof SplFileInfo) {
            $file = $file->getPathname();
        }

        // first we need to remove base path
        $file = str_replace(App::path(), '', $file);

        // then, convert to valid namespace
        return App::getNamespace() . trim(str_replace('/', '\\', str_replace('.php', '', $file)), '\\');
    }
}

if (! function_exists('pipeline')) {
    /**
     * @param  mixed          $passable
     * @param  class-string[] $pipes
     * @return Pipeline
     */
    function pipeline(mixed $passable = null, array $pipes = []) : mixed
    {
        $pipe = App::make(Pipeline::class);

        if (! $passable) {
            return $pipe;
        }

        $pipe->send($passable);

        if (! empty($pipes)) {
            return $pipe->through($pipes)->thenReturn();
        }

        return $pipe;
    }
}
