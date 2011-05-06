<?php

namespace App\WebBundle\Controller;

class DefaultController extends WebBaseController {

    public function indexAction() {
        $params = array();
        $this->searchByWord($params);

        //$params['itemPerPage'] = 1;
        $posts = $this->paginator('Post', $params);
        return $this->renderTpl('Default:index', compact('posts'));
    }

    private function searchByWord(&$params) {
        /**
         * Search by word
         */
        $query_get = $this->get('request')->query;
        $q = $query_get->get('q');
        if (!empty($q)) {
            $q = strtolower($q);
            $params['where'] = "LOWER(a.title) like '%$q%'";
        }
    }

}
