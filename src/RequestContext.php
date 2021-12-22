<?php

namespace Bosqu\RouteurPhp;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class RequestContext
{
    private UriInterface $uri;

    /**
     * RequestContext constructor.
     * @param UriInterface $uri
     */
    public function __construct(UriInterface $uri)
    {
        $this->uri = $uri;
    }

    /**
     * @param RequestInterface $request
     * @return RequestContext
     */
    public static function fromRequest(RequestInterface $request): RequestContext
    {
        return new self($request->getUri());
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->uri, $name], $arguments);
    }
}