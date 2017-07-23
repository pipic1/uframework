<?php
namespace Http;

use \Negotiation\Negotiator;

class Request
{
    const GET = 'GET';

    const POST = 'POST';

    const PUT = 'PUT';

    const DELETE = 'DELETE';

    private $parameters;

    public function __construct(array $query = array(), array $request = array())
    {
        $this->parameters = array_merge($query, $request);
    }

    public function getParameter($name, $default = null)
    {
        if (isset($this->parameters[$name])) {
            return $this->parameters[$name];
        }
        
        return $default;
    }

    public function getMethod()
    {
        $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : self::GET;
        if (self::POST === $method) {
            return $this->getParameter('_method', $method);
        }

        return $method;
    }

    public function getUri()
    {
        $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';

        if ($pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }

        return $uri;
    }

    public function guessBestFormat()
    {
        $negotiator = new Negotiator();

        $acceptHeader = $_SERVER['HTTP_ACCEPT'];
        $priorities = array('text/html; charset=UTF-8', 'application/json');

        $mediaType = $negotiator->getBest($acceptHeader, $priorities);

        return $mediaType->getValue();
    }

    public static function createFromGlobals()
    {
        if ('application/json' === (isset($_SERVER['HTTP_CONTENT_TYPE']) ? $_SERVER['HTTP_CONTENT_TYPE'] : null) || 'application/json' === (isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : null)) {
            $data = file_get_contents('php://input');
            $request = @json_decode($data, true);

            return new self($_GET, $request);
        }

        return new self($_GET, $_POST);
    }
}
