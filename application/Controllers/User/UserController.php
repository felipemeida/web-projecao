<?php

namespace Agencia\Close\Controllers\User;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\User\UserProduct;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Update;
use Agencia\Close\Conn\delete;

class UserController extends Controller
{
    private string $usuarios_listas_itens = 'usuarios_listas_itens';
    private string $usuarios_listas = 'usuarios_listas';

    public function saveProduct(array $params)
    {
        $this->saveProduct();
    }

    public function getListProduct(array $params)
    {
        $this->setParams($params);
        $userProduct = new UserProduct();

        $idCompany = $this->getDefault()['dataCompany']['id'];
        $idUser = $_SESSION['perfil_id'];
        $idProduct = $this->params['id-product'];
        $userList = $userProduct->findUserList($idCompany, $idUser, $idProduct)->getResult();

        $this->render('pages/user/userList.twig', ['userList' => $userList, 'idProduct' => $idProduct, 'idUser' => $idUser]);
    }


    public function actionItemList(array $params)
    {
        $this->setParams($params);
        
        if($this->params['action'] == 'insert'){
            $this->addItemList($_SESSION['perfil_id'], $this->params['idCompany'], $this->params['product'], $this->params['list']);
        }else{
            $this->removeItemList($_SESSION['perfil_id'], $this->params['idCompany'], $this->params['product'], $this->params['list']);
        }
    }

    public function saveNewLista(array $params)
    {
        $this->setParams($params);
        $this->addListProduct($_SESSION['perfil_id'], $this->params['idCompany'], $this->params['nameList'], $this->params['publico'], $this->params['itemList']);

    }

    public function addItemList($idUser, $idCompany, $product, $list)
    {
        $data = [
            'id_user' => $idUser,
            'id_empresa' => $idCompany,
            'id_lista' => $list,
            'id_item' => $product
        ];

        $Create = new Create();
        $Create->ExeCreate($this->usuarios_listas_itens, $data);
        echo 'delete';
    }

    public function removeItemList($idUser, $idCompany, $product, $list)
    {
        $Delete = new Delete();
        $Delete->ExeDelete($this->usuarios_listas_itens, "WHERE id_user = :idUser AND id_lista = :list AND id_item = :product ", "idUser={$idUser}&list={$list}&product={$product}");
        echo 'insert';
    }


    public function addListProduct($idUser, $idCompany, $nameList, $publico, $itemList)
    {
        $data = [
            'id_user' => $idUser,
            'id_empresa' => $idCompany,
            'nome' => $nameList,
            'slug' => $nameList,
            'publico' => $publico
        ];

        $Create = new Create();
        $Create->ExeCreate($this->usuarios_listas, $data);

        $user = new UserProduct();

        $LastListID = $user->getLastList($idUser, $idCompany, $nameList);
        $this->addItemList($idUser, $idCompany, $itemList, $LastListID);
    }

    public function totalCompare(array $params)
    {
        $this->setParams($params);
        $userProduct = new UserProduct();
        $totalCompare = $userProduct->totalCompare($_SESSION['perfil_id'], $this->params['idCompany']);
        echo $totalCompare;

    }

} 