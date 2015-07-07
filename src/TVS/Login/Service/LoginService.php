<?php

namespace TVS\Login\Service;

use Doctrine\ORM\EntityManager;
use TVS\Login\Entity\User;
use TVS\Base\Service\AbstractService;
use TVS\Application;

class LoginService extends AbstractService {

    public function __construct(EntityManager $em, User $user, Application $app) {
        parent::__construct($em, $app);
        $this->object = $user;
        $this->entity = "TVS\Login\Entity\User";
    }

    public function ajustaData(array $data = array()) {
        if ($data["password"] == '') {
            unset($data["password"]);
        }
        if (isset($data["image"])) {
            unset($data["image"]);
        }
        if ($data["ativo"]) {
            $data["ativo"] = 1;
            return $data;
        }
        $data["ativo"] = 0;
        return $data;
    }

    public function findByUsernameAndPassword($username, $password) {
        $repo = $this->em->getRepository($this->entity);
        return $repo->findByUsernameAndPassword($username, $password);
    }

    public static function uploadImage(array $files = array(), $username) {
        $types = ["jpeg"]; //Extensoes validas
        $completePath = __DIR__ . "/../../views";
        if (empty($files["tmp_name"]["image"])) {
            return false;
        }

        if (!is_dir("{$completePath}/profile")) {
            mkdir("{$completePath}/profile");
        }

        if (!is_dir("{$completePath}/profile/{$username}")) {
            mkdir("{$completePath}/profile/{$username}");
        }
        foreach ($types as $type) {
            if (strstr($files["type"]["image"], $type)) {
                $imagemPath = "/profile/{$username}/{$username}_" . time() . "." . $type;
                if (move_uploaded_file($files["tmp_name"]["image"], $completePath . $imagemPath)) {
                    return $imagemPath;
                }
                return false;
            }
        }
        return false;
    }

    publiC static function removeImage($path) {
        $completePath = __DIR__ . "/../../views";
        if (unlink($completePath . $path)) {
            return true;
        }
        return false;
    }

}
