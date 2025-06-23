<?php declare(strict_types=1);

namespace App\Darce\RestBundle\Model;

class TranslatableString
{
    /**
     * @var string
     */
    private $string;

    /**
     * @var array
     */
    private $params;

    public function __construct(string $string, array $params = [])
    {
        $this->string = $string;
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getString(): string
    {
        return $this->string;
    }

    /**
     * @param string $string
     */
    public function setString(string $string): void
    {
        $this->string = $string;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params): void
    {
        $this->params = $params;
    }
}