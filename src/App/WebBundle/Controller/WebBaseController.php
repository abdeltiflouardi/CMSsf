<?php

namespace App\WebBundle\Controller;

use App\CoreBundle\Controller\BaseController,
    App\CoreBundle\Twig\OutputExtension as Output;

class WebBaseController extends BaseController
{
    protected $_namespace = 'AppWebBundle:';

    public function renderTpl($action, $params = array(), $common = false) {
        $this->renderNavigation();
        $this->menu();
        $this->meta();
    
        return parent::renderTpl($action, $params, $common);
    }

    protected function renderNavigation() {
        // Home
        $navigation = array(
            array(
                'label' => 'Home',
                'url' => $this->generateUrl('_home')
            )
        );

        // Category & subcategory
        $category_id = $this->get('request')->get('category_id');
        if (is_numeric($category_id)) {
            $category = $this->findOne('Category', $category_id);
            if ($category->getParent() != null) {
                $subcategory = $category;
                $category = $category->getParent();
            }

            $navigation[] = array(
                'label' => $category->getName(),
                'url' => $this->generateUrl(
                                    '_category', 
                                    array(
                                         'category_id' => $category->getId(), 
                                         'slug' => Output::slug($category->getName())
                                    )
                                )
            );

            if (isset($subcategory)) {
                $navigation[] = array(
                    'label' => $subcategory->getName(),
                    'url' => $this->generateUrl(
                                        '_category', 
                                        array(
                                             'category_id' => $subcategory->getId(), 
                                             'slug' => Output::slug($subcategory->getName())
                                        )
                                     )
                );
            }
        }

        // Generate navigation of tag
        $tag_id = $this->get('request')->get('tag_id');
        $tag = $this->get('request')->get('tag');
        if ($tag_id) {
            $navigation[] = array(
                'label' => $tag,
                'url' => $this->generateUrl('_tag', array(
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

    protected function menu() {
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

        if (!empty($category_id))
            $category = $this->findOne('Category', $category_id);
        elseif ($post_id = $this->get('request')->get('post_id'))
            $category = $this->findOne('Post', $post_id)->getCategory();
        
        if (isset($category)) {
            if ($category->getParent() != null) {
                $parent = $category->getParent();
                $sub_categories = $parent->getSubCategories();

                $selected_menu = $parent->getName();
            } else {
                $sub_categories = $category->getSubCategories();
                $selected_menu = $category->getName();
            }
        }
		
        if (in_array($this->get("request")->get('_route'), array('_signin', '_forgotten_password', '_activate')))
            $selected_menu = 'Connexion';

        // Return datas to template
        $this->template = $this->get('twig');
        $this->template->addGlobal('selected_menu', $selected_menu);
        $this->template->addGlobal('categories', $categories);
        $this->template->addGlobal('sub_categories', $sub_categories);
    }

     protected function meta() {
         $this->template = $this->get('twig');
         $output = new Output();
 
         if (!array_key_exists('post', $this->_data)) {
             $title = $this->get('request')->get('slug', 'PHP Symfony Zend CakePHP');
             $title = $this->get('request')->get('tag', $title);
             $title = ucwords(strtolower($title));
             $description = '';
             $keywords = '';
         } else {
             $post = $this->_data['post'];
             $title = $post->getTitle();
             $description = $post->getBody();
             
             $item = array($post);
             foreach ($output->getTags($item) as $tag)
                 $keywords .= $tag->getName() . ', ';
 
             $keywords = rtrim(trim($keywords), ',');
         }
 
         $description = substr(preg_replace('/[ ]+/', ' ', preg_replace('/[^A-Za-z0-9]/', ' ',$description)), 0, 150);
 
         $this->template->addGlobal('meta_title', $title);
         $this->template->addGlobal('meta_keywords', $keywords);
         $this->template->addGlobal('meta_description', $description);
     }
}
