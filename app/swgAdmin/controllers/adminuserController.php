<?php
/*****************************************************************
 * RNDS SWG Account System
 * @author: Sidious <sidious@rnds.io>
 * @since: 15 April 2018
 * @link: https://github.com/SidiousRNDS/SWGRO-AccountSystem
 * @version: 1.0.0
 ******************************************************************
 * NAMESPACE: swgAS\swgAdmin\controllers
 * CLASS: adminuserController
 ******************************************************************/

namespace swgAS\swgAdmin\controllers;

// Use
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

// Use swgAS
use swgAS\config\settings;
use swgAS\controllers\baseController;
use swgAS\swgAdmin\models\adminroleModel;
use swgAS\swgAdmin\models\adminusersModel;
use swgAS\helpers\utilities;

class adminuserController extends baseController
{

    /**
     * Summary adminCreateUserView - Display the User Create Form
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     * @throws \Exception
     */
    public function adminCreateUserView(ServerRequestInterface $request, ResponseInterface $response)
    {
        $role = new adminroleModel();

        $getRoles = $role->getRoles([
            'mongodb' => $this->getCIElement('mongodb'),
            'flash'=>$this->getCIElement('flash')
        ]);

        $roles = json_decode($getRoles);

        return $this->getCIElement('views')->render($response, 'admincreateuserview.twig', [
            'flash'=>$this->getCIElement('flash'),
            'title'=>'Users',
            'route'=>$request->getUri()->getPath(),
            'roles' => $roles,
            'userRole' => unserialize($_SESSION['perms'])
        ]);
    }

    /**
     * Summary adminCreateUserAction - Add new helper to the DB
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     * @throws \ReflectionException
     */
    public function adminCreateUserAction(ServerRequestInterface $request, ResponseInterface $response)
    {
        $users = new adminusersModel();
        $addUser = $users->addUser([
            'mongodb' => $this->getCIElement('mongodb'),
            'flash'=>$this->getCIElement('flash'),
            'username' => utilities::sanitizeFormData($request->getParam('username'), FILTER_SANITIZE_STRING ),
            'password' => utilities::sanitizeFormData($request->getParam('password'), FILTER_SANITIZE_STRING ),
            'repassword' => utilities::sanitizeFormData($request->getParam('repassword'), FILTER_SANITIZE_STRING ),
            'email' => utilities::sanitizeFormData($request->getParam('email'), FILTER_SANITIZE_EMAIL ),
            'role' => utilities::sanitizeFormData($request->getParam('rolename'), FILTER_SANITIZE_STRING )
        ]);

        $role = new adminroleModel();

        $getRoles = $role->getRoles([
            'mongodb' => $this->getCIElement('mongodb'),
            'flash'=>$this->getCIElement('flash')
        ]);

        $roles = json_decode($getRoles);

        return $this->getCIElement('views')->render($response, 'admincreateuserview.twig', [
            'flash'=>$this->getCIElement('flash'),
            'title'=>'Users',
            'route'=>$request->getUri()->getPath(),
            'roles' => $roles
        ]);
    }

    public function adminViewUser(ServerRequestInterface $request, ResponseInterface $response)
    {
        $users = new adminusersModel();
        $getUsers = $users->getUsers([
            'mongodb' => $this->getCIElement('mongodb'),
            'flash'=>$this->getCIElement('flash')
        ]);

        $userData = json_decode($getUsers);

        return $this->getCIElement('views')->render($response, 'adminuserview.twig', [
            'flash'=>$this->getCIElement('flash'),
            'title'=>'Users',
            'route'=>$request->getUri()->getPath(),
            'users' => $userData,
            'userRole' => unserialize($_SESSION['perms'])
        ]);
    }

    /**
     * Summary adminUpdateUserView - Update helper form display
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     * @throws \Exception
     */
    public function adminUpdateUserView(ServerRequestInterface $request, ResponseInterface $response)
    {
        $route = $request->getAttribute('route');
        $args= $route->getArguments();

        $user = new adminusersModel();

        $userData = $user->getUser([
            'mongodb' => $this->getCIElement('mongodb'),
            'flash'=>$this->getCIElement('flash'),
            'id'=>$args['id']
        ]);

        $role = new adminroleModel();

        $getRoles = $role->getRoles([
            'mongodb' => $this->getCIElement('mongodb'),
            'flash'=>$this->getCIElement('flash')
        ]);

        $roles = json_decode($getRoles);

        return $this->getCIElement('views')->render($response, 'adminusersupdateview.twig', [
            'flash'=>$this->getCIElement('flash'),
            'title'=>'Users',
            'route'=>$request->getUri()->getPath(),
            'users' => $userData,
            'roles' => $roles,
            'id' => $args['id'],
            'userRole' => unserialize($_SESSION['perms'])
        ]);
    }

    public function adminUpdateUserAction(ServerRequestInterface $request, ResponseInterface $response)
    {
        $user = new adminusersModel();
        $user->updateUser([
            'mongodb' => $this->getCIElement('mongodb'),
            'flash'=>$this->getCIElement('flash'),
            'id' => $request->getParam('id'),
            'username' => utilities::sanitizeFormData($request->getParam('username'), FILTER_SANITIZE_STRING ),
            'password' => utilities::sanitizeFormData($request->getParam('password'), FILTER_SANITIZE_STRING ),
            'repassword' => utilities::sanitizeFormData($request->getParam('repassword'), FILTER_SANITIZE_STRING ),
            'email' => utilities::sanitizeFormData($request->getParam('email'), FILTER_SANITIZE_EMAIL ),
            'role' => utilities::sanitizeFormData($request->getParam('rolename'), FILTER_SANITIZE_STRING ),
            'changepassword' => $request->getParam('changepassword')
        ]);


        $uri = $request->getURI()->withPath($this->getCIElement('router')->pathFor('viewusers'));

        return $response->withRedirect($uri);
    }

    /**
     * Summary adminDeleteUsersAction - Remove User from the DB
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     */
    public function adminDeleteUserAction(ServerRequestInterface $request, ResponseInterface $response)
    {
        $route = $request->getAttribute('route');
        $args= $route->getArguments();

        //$id = $args['id'];

        $uri = $request->getURI()->withPath($this->getCIElement('router')->pathFor('viewusers'));

        $user = new adminusersModel();

        $delUser = $user->deleteUser([
            'mongodb' => $this->getCIElement('mongodb'),
            'flash'=>$this->getCIElement('flash'),
            'id'=>$args['id']
        ]);


        return $response->withRedirect($uri);
    }
}