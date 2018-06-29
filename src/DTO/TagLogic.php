<?php

namespace SZG\KunstmaanFeedBundle\DTO;

/**
 * Class TagLogic
 * @package SZG\KunstmaanFeedBundle\DTO
 */
class TagLogic
{

    const LOGIC_ANY = 'ANY';
    const LOGIC_FEW = 'FEW';
    const LOGIC_ALL = 'ALL';

    /** @var string */
    private $logic;

    /**
     * @param string $logic
     */
    public function __construct($logic)
    {
        $this->setLogic($logic);
    }

    private function setLogic($logic)
    {
        $logic = strtoupper($logic);
        if (!in_array($logic, self::getOptions())) {
            throw new \InvalidArgumentException('Unsupported tag logic, expected: ' . implode(',', self::getOptions()));
        }
        $this->logic = $logic;
    }

    /**
     * @param array $tags
     * @return int
     * @throws \Exception
     */
    public function getMinMatch(array $tags = [])
    {
        switch ((string)$this) {
            case TagLogic::LOGIC_ALL :
                return count($tags);
            case TagLogic::LOGIC_FEW :
                return max(1, ceil(count($tags) / 3));
            case TagLogic::LOGIC_ANY :
                return 1;
        }

        throw new \InvalidArgumentException('Unsupported tag logic');
    }

    /**
     * @return array
     */
    public static function getOptions()
    {
        return [self::LOGIC_ANY, self::LOGIC_ALL, self::LOGIC_FEW];
    }

    /**
     * @return string
     */
    function __toString()
    {
        return $this->logic;
    }

}
