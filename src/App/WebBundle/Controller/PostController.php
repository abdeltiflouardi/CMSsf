<?php

namespace App\WebBundle\Controller;

class PostController extends WebBaseController {

    public function indexAction() {
        /**
         * Menu & Navigation
         */
	$this->menu();
        $this->renderNavigation();

        /**
         * Select posts
         */
        $params = array();
        $this->searchByWord($params);
        $this->searchByCategory($params);
        $this->searchByTag($params);

        //$params['itemPerPage'] = 1;
        $posts = $this->paginator('Post', $params);
        return $this->renderTpl('Post:index', compact('posts'));
    }

    public function showAction($post_id) {
	/**
         * Menu & Navigation
         */
	$this->menu();
        $this->renderNavigation();


	$this->renderData(array('post' => $this->findOne('Post', $post_id)));

	$this->renderData(array('posts' => null));
	
	return $this->renderTpl('Post:show');
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
        if (!empty($category_id)) {
            $categories = $this->getRepo('Category')->findBy(array('parent' => $category_id));
            if (!empty($categories)) {
                $ids = array();
                foreach ($categories as $category) {
                    $ids[] = $category->getId();
                }

                if (!empty($ids)) {
                    $ids = implode(',', $ids);
                    $params['where'] = "a.category IN ($ids)";
                }
            }else
                $params['where'] = "a.category = $category_id";
        }
    }

    private function searchByTag(&$params) {
        $tag_id = $this->get('request')->get('tag_id');
        $tag = $this->get('request')->get('tag');
        if (!empty($tag_id)) {
            $tag = $this->findOne("Tag", $tag_id);
            foreach ($tag->getPost() as $post)
                $in[] = $post->getId();
            $in = implode(',', $in);
            $params['where'] = "a.id IN ($in)";
        }
    }

    private function renderNavigation() {
        // Home
        $navigation = array(
            array(
                'label' => 'Home',
                'url' => $this->generateUrl('_home')
            )
        );

        // Category & subcategory
        $category_id = $this->get('request')->get('category_id');
        if ($category_id) {            
	    $category = $this->findOne('Category', $category_id);
            if ($category->getParent() != null) {
		$subcategory = $category;
		$category = $category->getParent();
	    }
           
            $navigation[] = array(
                      'label' => $category->getName(),
                      'url' => $this->generateUrl('_category', array('category_id' => $category->getId(), 'slug' => $category->getName()))
           );

           if (isset($subcategory)) {
                $navigation[] = array(
                      'label' => $subcategory->getName(),
                      'url' => $this->generateUrl('_category', array('category_id' => $subcategory->getId(), 'slug' => $subcategory->getName()))
                );
           }
        }

        // Generate navigation of tag
        $tag_id = $this->get('request')->get('tag_id');
        $tag = $this->get('request')->get('tag');
        if ($tag_id) {           
            $navigation[] = array(
              'label' => $tag,
              'url' => $this->generateUrl('_tag',
                      array(
                          'tag_id' => $tag_id, 
                          'tag' => $tag,                
                          )
                      )
            );
        }         

        // Return navigation to template
        $this->template = $this->get('twig');
        $this->template->addGlobal('navigation', $navigation);        
    }

    public function menu() {
        /**
         * Menu
         */
        $categories = $this->getRepo('Category')->findBy(array('parent' => NULL));

        /**
         * Submenu
         */
	$selected_menu = "";
        $sub_categories = array();
        $category_id = $this->get('request')->get('category_id');
        if (!empty($category_id)) {
            $category = $this->findOne('Category', $category_id);
            if ($category->getParent() != null) {
                $parent = $category->getParent();
                $sub_categories = $parent->getSubCategories();     

                $selected_menu = $parent->getName();
            } else {
                 $sub_categories = $category->getSubCategories();

                 $selected_menu = $category->getName();
            }
        }

	// Return datas to template
        $this->template = $this->get('twig');
        $this->template->addGlobal('selected_menu', $selected_menu);
        $this->template->addGlobal('categories', $categories);        
        $this->template->addGlobal('sub_categories', $sub_categories);
    }
}
