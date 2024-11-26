<?php

namespace App\Bd;

use \PDO;
use \PDOException;

class User extends Conexion
{
    private int $id;
    private string $username;
    private string $email;
    private int $provincia_id;
    private string $imagen;

    private static function executeQuery($q, $options = [], bool $devolverAlgo = false)
    {
        $stmt = parent::getConexion()->prepare($q);
        try {
            (count($options)) ? $stmt->execute($options) : $stmt->execute();
        } catch (PDOException $ex) {
            throw new PDOException("Error>>: {$ex->getMessage()}");
        } finally {
            parent::cerrarConexion();
        }
        if ($devolverAlgo) return $stmt;
    }

    public  function create()
    {
        $q = "insert into users(username, email, provincia_id, imagen) values(:u, :e, :pi, :im)";
        self::executeQuery($q, [
            ':u' => $this->username,
            ':e' => $this->email,
            ':pi' => $this->provincia_id,
            'im' => $this->imagen
        ]);
    }

    public static function read(): array{
        $q="select users.*, nombre, color from users, provincias where provincia_id=provincias.id order by users.id desc";
        $stmt=self::executeQuery($q, [], true);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function existeCampo(string $nomCampo, string $valorCampo, ?int $id=null): bool{
        
        $q=(is_null($id)) ? "select count(*) as total from users where $nomCampo=:v" : 
        "select count(*) as total from users where $nomCampo=:v AND id != :i";

        $options=(is_null($id)) ? [':v'=>$valorCampo] : [':v'=>$valorCampo, ':i'=>$id];

        $stmt=self::executeQuery($q, $options, true);
        return $stmt->fetch(PDO::FETCH_OBJ)->total;
    }

    public static function crearUsersRandom(int $cant): void
    {
        $faker = \Faker\Factory::create('es_ES');
        $faker->addProvider(new \Mmo\Faker\FakeimgProvider($faker));
        $provinciasId = Provincia::getProvArrayId();
        for ($i = 0; $i < $cant; $i++) {
            $username = $faker->unique()->userName();
            $email = $username . "@" . $faker->freeEmailDomain();
            $provincia_id = $faker->randomElement($provinciasId);
            $imagen = "img/" . $faker->fakeImg(dir: "./../public/img/", width: 640, height: 480,
             fullPath: false, text: strtoupper(substr($username, 0, 3)), 
             backgroundColor: [random_int(0, 255), random_int(0, 255), random_int(0, 255)]);
            (new User)
            ->setUsername($username)
            ->setEmail($email)
            ->setProvinciaId($provincia_id)
            ->setImagen($imagen)
            ->create();
        }
    }


    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of username
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Set the value of username
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of provincia_id
     */
    public function getProvinciaId(): int
    {
        return $this->provincia_id;
    }

    /**
     * Set the value of provincia_id
     */
    public function setProvinciaId(int $provincia_id): self
    {
        $this->provincia_id = $provincia_id;

        return $this;
    }

    /**
     * Get the value of imagen
     */
    public function getImagen(): string
    {
        return $this->imagen;
    }

    /**
     * Set the value of imagen
     */
    public function setImagen(string $imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }
}
