<?php

namespace App\CoreBundle\Twig;

/**
 * Description of Output
 *
 * @author ouardisoft
 */
class OutputExtension extends \Twig_Extension
{

    /**
     * Returns a list of global functions to add to the existing list.
     *
     * @return array An array of global functions
     */
    public function getFunctions()
    {
        return array(
            'tags'  => new \Twig_Function_Method($this, 'getTags'),
            'count' => new \Twig_Function_Method($this, 'getCount'),
            'slug'  => new \Twig_Function_Method($this, 'slug'),
        );
    }

    /**
     * Returns unique tags
     *
     * @param ArrayCollection $posts
     * @return array
     */
    public function getTags($posts)
    {
        $tags = array();
        foreach ($posts as $post) {
            foreach ($post->getTag() as $tag) {
                if (!in_array($tag, $tags)) {
                    $tags[] = $tag;
                }
            }
        }
        return $tags;
    }

    /**
     * Returns count of items
     *
     * @param array $posts
     * @return integer
     */
    public function getCount($items)
    {
        return count($items);
    }

    /**
     * Create a slug from text
     */
    public static function slug($url)
    {
        $url = preg_replace("`\[.*\]`U", "", $url);
        $url = preg_replace('`&(amp;)?#?[a-z0-9]+;`i', '-', $url);
        $url = htmlentities($url, ENT_NOQUOTES, 'UTF-8');
        $url = preg_replace("`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i", "\\1", $url);
        $url = preg_replace(array("`[^a-z0-9]`i", "`[-]+`"), "-", $url);
        $url = ( $url == "" ) ? $type : strtolower(trim($url, '-'));
        return $url;
    }

    /**
     * Name of this extension
     *
     * @return string
     */
    public function getName()
    {
        return 'output';
    }
}
