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

    public function adminCreateRoleView(ServerRequestInterface $request, ResponseInterface $response)
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
    public function adminCreateRoleAction(ServerRequestInterface $request, ResponseInterface $response)
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

    public function adminRoleView(ServerRequestInterface $request, ResponseInterface $response)
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

    public function adminUpdateRoleView(ServerRequestInterface $request, ResponseInterface $response)
    {
        $route = $request->getAttribute('route');
        $args= $route->getArguments();

        $role = new adminroleModel();

        $getRole = $role->getRole([
            'mongodb' => $this->getCIElement('mongodb'),
            'flash'=>$this->getCIElement('flash'),
            'id'=>$args['id']
        ]);

        $roleData = json_decode($getRole);

        return $this->getCIElement('views')->render($response, 'adminupdaterole.twig', [
            'flash'=>$this->getCIElement('flash'),
            'title'=>'Update Role',
            'route'=>$request->getUri()->getPath(),
            'role' => $roleData,
            'sections' => settings::ADMIN_SECTIONS
        ]);
    }

    public function adminUpdateRoleAction(ServerRequestInterface $request, ResponseInterface $response)
    {
        $role = new adminroleModel();

        $update = $role->updateRole([
            'mongodb' => $this->getCIElement('mongodb'),
            'flash' => $this->getCIElement('flash'),
            'request'=>$request->getParsedBody()
        ]);


        $id = $request->getParam('id');

        $getRole = $role->getRole([
            'mongodb' => $this->getCIElement('mongodb'),
            'flash'=>$this->getCIElement('flash'),
            'id'=>$id
        ]);

         $roleData = json_decode($getRole);

        return $this->getCIElement('views')->render($response, 'adminupdaterole.twig', [
            'flash'=>$this->getCIElement('flash'),
            'title'=>'Update Role',
            'route'=>$request->getUri()->getPath(),
            'role' => $roleData
        ]);
    }

    public function adminDeleteRoleAction(ServerRequestInterface $request, ResponseInterface $response)
    {
        $route = $request->getAttribute('route');
        $args= $route->getArguments();
        $id = $args['id'];

        $uri = $request->getURI()->withPath($this->getCIElement('router')->pathFor('viewroles'));

        $role = new adminroleModel();

        $delRole = $role->deleteRole([
            'mongodb' => $this->getCIElement('mongodb'),
            'flash'=>$this->getCIElement('flash'),
            'id'=>$args['id']
        ]);


        return $response->withRedirect($uri);
    }
}