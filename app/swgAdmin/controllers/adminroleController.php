<?php
/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 12 April 2018
 * @link: https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version: 1.0.0
 ******************************************************************
 * NAMESPACE: swgAS\swgAdmin\controllers
 * CLASS: adminroleController
 ******************************************************************/

namespace swgAS\swgAdmin\controllers;

// Use
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

// Use swgAS
use swgAS\config\settings;
use swgAS\controllers\baseController;
use swgAS\swgAdmin\models\adminroleModel;
use swgAS\utils\utilities;


class adminroleController extends baseController
{

    public function adminCreateRole(ServerRequestInterface $request, ResponseInterface $response)
    {
        $role = new adminroleModel();

        $getRoles = $role->getRoles([
            'mongodb' => $this->getCIElement('mongodb'),
            'flash'=>$this->getCIElement('flash')
        ]);

        return $this->getCIElement('views')->render($response, 'admincreaterole.twig', [
            'flash'=>$this->getCIElement('flash'),
            'title'=>'Roles',
            'route'=>$request->getUri()->getPath(),
            'roles' => $getRoles,
            'sections' => settings::ADMIN_SECTIONS
        ]);


    }

    /**
     * Summary adminCreateRole - Create new role
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     * @throws \ReflectionException
     */
    public function adminGenerateRole(ServerRequestInterface $request, ResponseInterface $response)
    {
        $role = new adminroleModel();

        $addRole = $role->addRole([
            'mongodb' => $this->getCIElement('mongodb'),
            'flash'=>$this->getCIElement('flash'),
            'request'=>$request->getParsedBody()
        ]);

        $getRoles = $role->getRoles([
            'mongodb' => $this->getCIElement('mongodb'),
            'flash'=>$this->getCIElement('flash')
        ]);

        return $this->getCIElement('views')->render($response, 'admincreaterole.twig', [
            'flash'=>$this->getCIElement('flash'),
            'title'=>'Roles',
            'route'=>$request->getUri()->getPath(),
            'roles' => $getRoles,
            'sections' => settings::ADMIN_SECTIONS
        ]);
    }

    public function adminViewRole(ServerRequestInterface $request, ResponseInterface $response)
    {
        $role = new adminroleModel();

        $getRoles = $role->getRoles([
            'mongodb' => $this->getCIElement('mongodb'),
            'flash'=>$this->getCIElement('flash')
        ]);

        $roles = json_decode($getRoles);

        return $this->getCIElement('views')->render($response, 'adminviewrole.twig', [
            'flash'=>$this->getCIElement('flash'),
            'title'=>'View Roles',
            'route'=>$request->getUri()->getPath(),
            'roles' => $roles,
            'sections' => settings::ADMIN_SECTIONS
        ]);
    }

    public function adminUpdateRole(ServerRequestInterface $request, ResponseInterface $response)
    {
        $role = new adminroleModel();

        return $this->getCIElement('views')->render($response, 'adminupdatewrole.twig', [
            'flash'=>$this->getCIElement('flash'),
            'title'=>'Update Role',
            'route'=>$request->getUri()->getPath(),
            //'roles' => $roles,
            //'sections' => settings::ADMIN_SECTIONS
        ]);
    }
}