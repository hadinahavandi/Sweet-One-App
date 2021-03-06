<?php

namespace Modules\products\EntityObjects;

use core\CoreClasses\services\EntityObject;
/**
 *
 * @author Hadi Nahavandi
 *        
 */
class ProductEO extends EntityObject{
	private $id;
	private $adddate;
	private $visits;
	private $rank;
	private $description;
	private $thumburl;
	private $mainphoto;
	private $title;
	private $latinname;
	private $ispublished;
	private $isdeleted;
	private $productgroups;
	private $userid;
	public function setId($id)
	{
		$this->id=$id;
	}
	public function setAdddate($adddate)
	{
		$this->adddate=$adddate;
	}
	public function setVisits($visits)
	{
		$this->visits=$visits;
	}
	public function setRank($rank)
	{
		$this->rank=$rank;
	}
	public function setDescription($description)
	{
		$this->description=$description;
	}
	public function setThumburl($thumburl)
	{
		$this->thumburl=$thumburl;
	}
	public function setMainphoto($mainphoto)
	{
		$this->mainphoto=$mainphoto;
	}
	public function setTitle($title)
	{
		$this->title=$title;
	}
	public function setLatinname($latinname)
	{
		$this->latinname=$latinname;
	}
	public function setIspublished($ispublished)
	{
		$this->ispublished=$ispublished;
	}
	public function setIsdeleted($isdeleted)
	{
		$this->isdeleted=$isdeleted;
	}
	public function setProductgroups($productgroups)
	{
		$this->productgroups=$productgroups;
	}
	public function setUserid($userid)
	{
		$this->userid=$userid;
	}
	public function getId()
	{
		return $this->id;
	}
	public function getAdddate()
	{
		return $this->adddate;
	}
	public function getVisits()
	{
		return $this->visits;
	}
	public function getRank()
	{
		return $this->rank;
	}
	public function getDescription()
	{
		return $this->description;
	}
	public function getThumburl()
	{
		return $this->thumburl;
	}
	public function getMainphoto()
	{
		return $this->mainphoto;
	}
	public function getTitle()
	{
		return $this->title;
	}
	public function getLatinname()
	{
		return $this->latinname;
	}
	public function getIspublished()
	{
		return $this->ispublished;
	}
	public function getIsdeleted()
	{
		return $this->isdeleted;
	}
	public function getProductgroups()
	{
		return $this->productgroups;
	}
	public function getUserid()
	{
		return $this->userid;
	}
}

?>