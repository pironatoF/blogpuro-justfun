<?php

namespace Justfun\Controllers;

use Justfun\Controllers\Controller as Controller;
use Justfun\Core\Factory as CoreFactory;
use Justfun\Core\Response as Response;
use Justfun\Services\Factory as servicesFactory;
use Justfun\Repositories\Factory as RepositoriesFactory;
use Justfun\Repositories\postsRepository as postsRepository;
use Justfun\Services\urlManagerService as urlManagerService;
use Justfun\Traits\dataPersistenceTrait as dataPersistenceTrait;
use Justfun\Entities\postCommentEntity as postCommentEntity;

/**
 * Class postCommentsController
 *
 * @author Pironato Francesco
 * 
 */
class postCommentsController extends Controller {

    public function init() {
        parent::init();
        $this->response = CoreFactory::getResponse();

        $this->response->setData(array('title' => 'Article, comment - BlogPuro'));
    }

    public function listAction() {
        $this->response->addStylesheet('author');
        $this->response->addStylesheet('admin');
        $this->response->setLayout('author');
        $this->response->setActiveNav('post-comments-list');
        $getData = $this->request->getGetData();
        $repository = RepositoriesFactory::getPostCommentsRepository();
        $currentUser = servicesFactory::getAuthService()->getUser();
        if (!isset($getData['page']))
            $getData['page'] = 1; // forzo la paginazione
        $page = $getData['page'];
        $numberPerPage = 5;  // determino il numero di post da visualizzare per pagina
        $count = (int) $repository->count(array('user_id' => $currentUser->getId()))['data']['count'];
        $paginator = servicesFactory::getPaginatorService($page, $count, $numberPerPage);
        $paginator->setBaseUrl('/post-comments/list/');
        $filters = array(
            'user_id' => $currentUser->getId()
        );
        $data['comments'] = $repository->search($filters, 'DESC', $paginator);
        $data['paginator'] = $paginator;
        $data['controllerViewFolder'] = 'post-comments';
        $this->response->setType(Response::RESPONSE_HTML);
        $this->response->setData($data);
        $this->response->render();
    }

    public function editAction() {
        $this->response->addStylesheet('author');
        $this->response->addStylesheet('admin');
        $this->response->setLayout('author');
        $this->response->setActiveNav('post-comments-edit');
        $repository = RepositoriesFactory::getPostCommentsRepository();
        $id = servicesFactory::getUrlManagerService($this->request->getRequestUri(), urlManagerService::URL_EDIT, 'post-comments')->getUrl();

        $formData = $this->request->getPostData();
        $entity = $repository->find($id);

        if ($formData) {
            // @TODO: implementare validatori
            $entity = dataPersistenceTrait::save($formData, $entity);
            $repository->store($entity);
            $this->response->redirect($entity->getPost()->getUrl().'#postComments');
            //$this->response->redirect('/author/index/?op=ok');
        }

        if ($entity) {
            $data['comment'] = $entity;
        } else {
            // non è stato trovato nessun articolo.. @TODO:gestire eccezione(fancy)
        }
        $data['controllerViewFolder'] = 'post-comments'; // reverse camel-case x search controller(folder)
        $this->response->setIsActiveTinymce(false); // disattiva tinymce
        $this->response->setType(Response::RESPONSE_HTML);
        $this->response->setData($data);
        $this->response->render();
    }


    //@TODO: edit url solamente per admin (gestire con acl)
    public function addAction() {
        $data = array();
        $formData = $this->request->getPostData();
        $entityData = array();
        $repository = RepositoriesFactory::getPostCommentsRepository();
        // se non c'è il post_id faccio il redirect al post con ancora e mosto un errore
        if (!isset($formData['post_id']) || empty($formData['post_id'])) {
            if (isset($formData['to_redirect']))
                $this->response->redirect($formData['to_redirect']);
            die('error, no redirect provided');
        }else {
            $entityData['post_id'] = (int) $formData['post_id'];
        }

        if ((!isset($formData['comment_message']) || empty($formData['comment_message']))
        ) {
            // se non c'è il post_id faccio il redirect al post con ancora e mosto un errore
            if (isset($formData['to_redirect']))
                $this->response->redirect($formData['to_redirect']);
            die('error, no redirect provided');
        }else {
            // @TODO: implementare validatori

            $entityData['message'] = $formData['comment_message'];

            if (servicesFactory::getAuthService()->isLogged()) {
                $entityData['user_id'] = servicesFactory::getAuthService()->getUser()->getId();
                $entityData['is_guest'] = 0;
            } else {
                // anonymus
                $entityData['user_id'] = null;
                $entityData['is_guest'] = 1;
            }

            $entity = dataPersistenceTrait::save($entityData, new postCommentEntity());
            $repository->store($entity);
            // fare redirect al post con ancora al commento
            if (isset($formData['to_redirect']))
                $this->response->redirect($formData['to_redirect']);
            die('error, no redirect provided');
        }
    }

    public function deleteAction() {
        // per ora solo gli utenti possono cancellare il commento
        if (!$this->response->getAuthService()->isLogged())
            $this->response->redirect('/auth/login');
        $repository = RepositoriesFactory::getPostCommentsRepository();
        $id = servicesFactory::getUrlManagerService($this->request->getRequestUri(), urlManagerService::URL_DELETE, 'post-comments')->getUrl();
        /**
         *  @todo: dare la possibilità di settare il messaggio di errore da passare a response
         *  (creare un array come propietà e sfruttare il metodo gia esistente degli alert per
         *   il render)
         */
        $deleteOp = $repository->delete($id);
        if (false === $deleteOp['status']) {
            // qui fare il set dell'errore contenuto in $deleteOp['error']
            $this->response->redirect('/post-comments/list/?op=ok');
        }
        $this->response->redirect('/post-comments/list/?op=ok');
    }

}
