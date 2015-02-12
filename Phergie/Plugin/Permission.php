<?php
class Phergie_Plugin_Permission extends Phergie_Plugin_Abstract
{
	// Grabbing config items and putting them into variables
	
	public function getLevel($hostmask)
	{
		$owner = $this->getConfig('permission.owner');
		$admin = $this->getConfig('permission.admin');
		$black = $this->getConfig('permission.black');
		
		if (in_array(strtolower($hostmask), $owner))
		{
			return 3;
		} else if (in_array(strtolower($hostmask), $admin))
			{
				return 2;
			} else if (in_array(strtolower($hostmask), $black))
				{
					return 1;
				} else 
					{
						return 0;
					}
	}
	
	public function isOwner($hostmask)
	{
		$owner = $this->getConfig('permission.owner');
		$admin = $this->getConfig('permission.admin');
		$black = $this->getConfig('permission.black');
		
		if (in_array(strtolower($hostmask), $owner))
		{
			return true;
		} else {
			return false;
		}
	}
	
	public function isAdmin($hostmask)
	{
		$owner = $this->getConfig('permission.owner');
		$admin = $this->getConfig('permission.admin');
		$black = $this->getConfig('permission.black');
	
		if (in_array(strtolower($hostmask), $admin))
		{
			return true;
		} else {
			return false;
		}
	}
	
	public function isBlacklisted($hostmask)
	{
		$owner = $this->getConfig('permission.owner');
		$admin = $this->getConfig('permission.admin');
		$black = $this->getConfig('permission.black');
	
		if (in_array(strtolower($hostmask), $black))
		{
			return true;
		} else {
			return false;
		}
	}
}