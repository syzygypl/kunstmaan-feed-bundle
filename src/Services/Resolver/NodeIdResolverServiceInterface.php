<?php

namespace SZG\KunstmaanFeedBundle\Services\Resolver;

interface NodeIdResolverServiceInterface
{

    /**
     * @param mixed $value
     * @return int
     */
    public function resolve($value);
    
}
