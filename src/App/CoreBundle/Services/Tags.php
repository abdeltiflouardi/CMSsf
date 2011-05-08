<?php

namespace App\CoreBundle\Services;

use Symfony\Component\HttpFoundation\Request;

class Tags {

	protected $request;

	public function __construct(Request $request) {
		$this->request = $request;
	}
	
	/**
	 * @return array $tags
	 */
	public function parseTags() {
		$posted_post = $this->request->get('post');
		
		if (!$posted_post)
			return null;

		$tags = preg_split('/[,;]/', $posted_post['words']);
		$tags = array_map('trim', $tags);
		return $tags;		
	}

	public function addTags() {
		$tags = $this->parseTags();
		if (!$tags)
			return null;

		
	}

	public function editTags() {
                $tags = $this->parseTags();
                if (!$tags)
                        return null;
	}

	public function deleteTags() {
                $tags = $this->parseTags();
                if (!$tags)
                        return null;
	}
}
