<?php
/**
 * Created by PhpStorm.
 * User: Thiago
 * Date: 04/02/2018
 * Time: 12:41
 */

namespace Thiagocfn\MikrotikConnection;

use PEAR2\Net\RouterOS;
use PEAR2\Net\RouterOS\Exception;
use PEAR2\Net\RouterOS\Response;
use Thiagocfn\MikrotikConnection\Exceptions\ConnectionException;

class Client
{
    private $host;
    private $login;
    private $password;

    private $client;

    /**
     * Client constructor.
     * @param $host
     * @param $login
     * @param $password
     */
    public function __construct($host, $login, $password)
    {
        $this->host = $host;
        $this->login = $login;
        $this->password = $password;
    }

    /**
     * Verifica quantos dispositivos estão conectados no hotspot
     * @return int quantidade de dispositivos conectados
     * @throws \Thiagocfn\MikrotikConnection\Exceptions\ConnectionException
     */
    public function count()
    {
        $count = 0;
        try {
            $this->client = new RouterOS\Client($this->host, $this->login, $this->password);

            $responses = $this->client->sendSync(new RouterOS\Request('/ip/arp/print'));
            foreach ($responses as $response) {
                if ($response->getType() === Response::TYPE_DATA) {
                    $count++;
                }
            }
            return $count;
        } catch (Exception $e) {
            throw new ConnectionException("Não foi possivel conectar ao mikrotik.", $e);
        } finally {
            $this->client->close();
        }
    }

    /**
     * Verifica se há dispositivos conectados na rede
     * @return bool verdadeiro caso hajam dispositivos conectados. Falso caso contrário
     * @throws \Thiagocfn\MikrotikConnection\Exceptions\ConnectionException em caso de falha de conexão com o mikrotik.
     */
    public function hasUsers()
    {
        return $this->count() > 0;
    }

    /**
     * Verifica se há dispositivos conectados na red
     * @return int um(1) caso hajam dispositivos conectados. zero(0) caso contrário
     * @throws \Thiagocfn\MikrotikConnection\Exceptions\ConnectionException
     */
    public function hasIntUsers()
    {
        return $this->hasUsers() ? 1 : 0;
    }



    // Getters and setters
    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param mixed $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }


}