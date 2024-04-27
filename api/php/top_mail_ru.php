<?php

/**
 * Class TopMailRu
 */
class TopMailRu
{
    const BASE_URL = 'https://top.mail.ru';

    // Instance variables
    private $apiKey;
    private $returnAsArray;
    private $session;

    /**
     * TopMailRu constructor.
     * @param string $apiKey API key
     * @param bool $returnAsArray Whether to return as array
     */
    public function __construct(string $apiKey, bool $returnAsArray)
    {
        $this->apiKey = $apiKey;
        $this->returnAsArray = $returnAsArray;
    }

    /**
     * Performs bitwise XOR operation between two strings.
     * @param string $o1 String 1
     * @param string $o2 String 2
     * @return string Result of XOR operation
     */
    protected function bitxor(string $o1, string $o2): string
    {
        $result = '';
        $runs = strlen($o1);
        for ($i = 0; $i < $runs; $i++) {
            $result .= $o1[$i] ^ $o2[$i];
        }
        return $result;
    }

    /**
     * Performs an HTTP request.
     * @param string $path URL path
     * @param array $argsArray Array of arguments
     * @param bool $returnAsArray Whether to return as array
     * @return mixed|null Returned data
     */
    protected function request(string $path, array $argsArray, bool $returnAsArray): mixed
    {
        $url = static::BASE_URL . $path . '?' . http_build_query($argsArray);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = null;
        try {
            $data = curl_exec($ch);
            curl_close($ch);
            $data = json_decode($data, $returnAsArray);
        } catch (Exception $e) {
            // Improve exception handling here
            echo "Exception: {$e->getCode()} ({$e->getMessage()})", PHP_EOL;
        }
        return $data;
    }

    /**
     * Returns the key and session arguments.
     * @return array Key and session arguments
     */
    protected function getKeyAndSession(): array
    {
        $args = [];
        if ($this->apiKey) {
            $args['apikey'] = $this->apiKey;
        }
        if ($this->session) {
            $args['session'] = $this->session;
        }
        return $args;
    }

    /**
     * Registers a site.
     * @param array $args Arguments for registration
     * @return mixed|null Response from the server
     */
    public function registerSite(array $args): mixed
    {
        $args += $this->getKeyAndSession();
        return $this->request('/json/add', $args, $this->returnAsArray);
    }

    /**
     * Edits a site.
     * @param int $id Site ID
     * @param string $password Site password
     * @param array $args Arguments for editing
     * @return mixed|null Response from the server
     */
    public function editSite(int $id, string $password, array $args): mixed
    {
        $args += $this->getKeyAndSession();
        $args['id'] = $id;
        $args['password'] = $password;
        return $this->request('/json/edit', $args, $this->returnAsArray);
    }

    /**
     * Retrieves code for a site.
     * @param int $id Site ID
     * @param string $password Site password
     * @param array $args Additional arguments
     * @return mixed|null Response from the server
     */
    public function getCode(int $id, string $password, array $args): mixed
    {
        $args += $this->getKeyAndSession();
        $args['id'] = $id;
        $args['password'] = $password;
        return $this->request('/json/code', $args, $this->returnAsArray);
    }

    /**
     * Sets the session.
     * @param string $session Session ID
     */
    public function setSession(string $session): void
    {
        $this->session = $session;
    }

    /**
     * Logs in.
     * @param int $id Site ID
     * @param string $password Site password
     * @return bool Whether the login was successful
     */
    public function login(int $id, string $password): bool
    {
        $args = array_merge(
            array('id' => $id, 'password' => $password, 'action' => 'json'),
            $this->getKeyAndSession()
        );
        $res = $this->request('/json/login', $args, true);
        if ($res['session']) {
            $this->session = $res['session'];
        }
        return isset($res['logged']) && $res['logged'] === 'yes';
    }

    /**
     * Retrieves statistics for a site.
     * @param int $id Site ID
     * @param string $password Site password
     * @param string $type Type of statistics
     * @param array $args Additional arguments
     * @return mixed|null Response from the server
     */
    public function getStat(int $id, string $password, string $type, array $args): mixed
    {
        $args += $this->getKeyAndSession();
        $args['id'] = $id;
        $args['password'] = $password;
        return $this->request('/json/' . $type, $args, $this->returnAsArray);
    }
}