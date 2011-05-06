<?php

namespace App\WebBundle\Controller;

class DefaultController extends WebBaseController {

    public function indexAction() {
        $categories = $this->getAll('Category');
        
        $params = array();
        $this->searchByWord($params);
        $this->searchByCategory($params);

        //$params['itemPerPage'] = 1;
        $posts = $this->paginator('Post', $params);
        return $this->renderTpl('Default:index', compact('posts', 'categories'));
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
    
    private function searchByCategory(&$params) {
        $category_id = $this->get('request')->get('category_id');
        $slug = $this->get('request')->get('slug');
        if (!empty($category_id)) {
            $params['where'] = "a.category = $category_id";
        }
    }

}
