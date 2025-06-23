<?php declare(strict_types=1);

namespace App\Darce\RestBundle\Model;

class GenericHashMap
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $label;

    public function __construct(string $key, string $label)
    {
        $this->label = $label;
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label): void
    {
        $this->label = $label;
    }
}