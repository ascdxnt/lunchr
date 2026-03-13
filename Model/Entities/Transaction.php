<?php

class Transaction
{
	private $id;
	private $studentId;
	private $teacherId;
	private $date;
	private $time;
	private $meals;
	private $status;

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getStudentId()
	{
		return $this->studentId;
	}

	public function setStudentId($studentId)
	{
		$this->studentId = $studentId;
	}

	public function getTeacherId()
	{
		return $this->teacherId;
	}

	public function setTeacherId($teacherId)
	{
		$this->teacherId = $teacherId;
	}

	public function getDate()
	{
		return $this->date;
	}

	public function setDate($date)
	{
		$this->date = $date;
	}

	public function getTime()
	{
		return $this->time;
	}

	public function setTime($time)
	{
		$this->time = $time;
	}

	public function getMeals()
	{
		return $this->meals;
	}

	public function setMeals($meals)
	{
		$this->meals = $meals;
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
