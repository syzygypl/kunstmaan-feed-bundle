<?php

namespace SZG\KunstmaanFeedBundle\Feed\ElasticSearch\Chain\Exception;

class FeedDoesNotExistException extends \Exception
{

    protected $message = 'Feed %s does not exist.';

    /**
     * @param string $feedName
     */
    public function __construct($feedName)
    {
        $this->message = sprintf($this->message, $feedName);
    }

}
