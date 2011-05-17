<?php

namespace App\CoreBundle\Services;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use App\CoreBundle\Entity\Tag;

class Tags {

	protected $_request;
	protected $_em;
	protected $_namespace = 'AppCoreBundle';

	public function __construct(Request $request, EntityManager $em) {
		$this->_request = $request;
		$this->_em = $em;
	}
	
	/**
	 * @return array $tags
	 */
	public function parseTags() {
		$posted_post = $this->_request->get('post');
		
		if (!$posted_post)
			return null;

		$tags = preg_split('/[,;]/', $posted_post['words']);
		$tags = array_map('trim', $tags);
		$tags = array_map('strtolower', $tags);
		return $tags;		
	}

	public function addTags($id) {
		$tags = $this->parseTags();
		if (!$tags)
			return null;

		$post = $this->_em->find($this->_namespace . ':Post', $id);
		foreach ($tags as $tag_name) {
			$tag = $this->_em->getRepository($this->_namespace . ':Tag')->findOneByName($tag_name);
			if (!$tag) {
				$tag = new Tag();
				$tag->setName($tag_name);
				$this->_em->persist($tag);
				$this->_em->flush();
			}
			
			$post->getTag()->add($tag);
			$tag->getPost()->add($post);
	
		        $em = $this->_em;
		        $em->persist($post);
		        $em->persist($tag);
		        $em->flush();		
		}

	}

	public function editTags($id) {
		$tags = $this->parseTags();
		if (!$tags)
			return null;

	
		$post = $this->_em->find($this->_namespace . ':Post', $id);
		foreach ($post->getTag() as $tag) {
			$post->getTag()->removeElement($tag);
			$tag->getPost()->removeElement($post);

			$this->_em->persist($post);
			$this->_em->persist($tag);
			$this->_em->flush();
		}

		$this->addTags($id);
	}

}
