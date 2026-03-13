<?php

class Staff
{
	private $id;
	private $profile;
	private $name;
	private $firstLastName;
	private $secondLastName;
	private $email;
	private $password;
	private $status;

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getProfile()
	{
		return $this->profile;
	}

	public function setProfile($profile)
	{
		$this->profile = $profile;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getFirstLastName()
	{
		return $this->firstLastName;
	}

	public function setFirstLastName($firstLastName)
	{
		$this->firstLastName = $firstLastName;
	}

	public function getSecondLastName()
	{
		return $this->secondLastName;
	}

	public function setSecondLastName($secondLastName)
	{
		$this->secondLastName = $secondLastName;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function setEmail($email)
	{
		$this->email = $email;
	}

	public function getPassword()
	{
		return $this->password;
	}

	public function setPassword($password)
	{
		$this->password = $password;
	}

	public function getStatus()
	{
		return $this->status;
	}

	public function setStatus($status)
	{
		$this->status = $status;
	}
}
