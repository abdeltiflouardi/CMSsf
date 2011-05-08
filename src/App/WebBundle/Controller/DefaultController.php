<?php

namespace App\WebBundle\Controller;

class DefaultController extends WebBaseController {

    public function indexAction() {
        $categories = $this->getAll('Category');
       
        $params = array();
        $this->searchByWord($params);
        $this->searchByCategory($params);
	$this->searchByTag($params);

        //$params['itemPerPage'] = 1;
        $posts = $this->paginator('Post', $params);
        return $this->renderTpl('Default:index', compact('posts', 'categories'));
    }

    private function searchByWord(&$params) {
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

    private function searchByTag(&$params) {
        $tag_id = $this->get('request')->get('tag_id');
        $tag = $this->get('request')->get('tag');
        if (!empty($tag_id)) {
	    $tag = $this->findOne("Tag", $tag_id);
	    foreach($tag->getPost() as $post) 
		$in[] = $post->getId();
	    $in = implode(',', $in);	    
            $params['where'] = "a.id IN ($in)";
        }
    }

}
