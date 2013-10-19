<?php
namespace Ibw\JobeetBundle\Utils;

class Paginator
{
	public $total_count;
	public $per_page;
	public $current_page;

	public $pages_count;
	public $offset;

	public function init($total_count, $per_page, $current_page)
	{
		$this->total_count = $total_count;
		$this->per_page = $per_page;
		$this->current_page = $current_page;

		$this->pages_count = ceil($this->total_count / $this->per_page);
		$this->offset = $current_page <= $this->pages_count ? $this->per_page * ($this->current_page - 1) : $this->per_page * ($this->pages_count - 1);
		// $this->offset = $this->per_page * ($this->current_page - 1);	
	}

	public function prevPage()
	{
		return $this->current_page - 1;
	}

	public function nextPage()
	{
		return $this->current_page + 1;
	}

	public function hasPrev()
	{
		return $this->prevPage() >= 1;
	}

	public function hasNext()
	{
		return $this->nextPage() <= $this->pages_count;
	}
}
?>