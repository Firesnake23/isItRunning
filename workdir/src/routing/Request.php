<?php

namespace firesnake\isItRunning\routing;

class Request
{
    private function __construct() {}

    private array $get = [];
    private array $post = [];
    private array $files = [];
    private $uri = null;

    public static function createRequest() :Request
    {
        $request = new Request();
        $len = strpos($_SERVER['REQUEST_URI'], '?');
        if($len === false) {
            $len = strlen($_SERVER['REQUEST_URI']);
        }
        $request->uri = substr($_SERVER['REQUEST_URI'], 0, $len);

        $request->get = $_GET;
        $request->post = $_POST;
        $request->file = $_FILES;

        $jsonObjects = json_decode(file_get_contents('php://input'), true);
        if($jsonObjects != null) {
            $request->post = array_merge($request->post, $jsonObjects);
        }

        return $request;
    }

    /**
     * @return array
     */
    public function getGet(): array
    {
        return $this->get;
    }

    /**
     * @return array
     */
    public function getPost(): array
    {
        return $this->post;
    }

    /**
     * @return array
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * @return null
     */
    public function getUri()
    {
        return $this->uri;
    }
}