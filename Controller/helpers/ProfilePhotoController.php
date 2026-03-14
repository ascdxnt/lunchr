<?php

class ProfilePhotoController
{
	public function GenerateProfilePhoto($name, $tmp_path, $size, $type)
	{
		if ($name == null || $name == "") {
			return "./View/assets/profile/default.jpg";
		}

		if (!$this->ValidateImage($type, $size)) {
			return false;
		}

		$extension = pathinfo($name, PATHINFO_EXTENSION);
		$directory = $_SERVER['DOCUMENT_ROOT'] . '/View/assets/profile/';
		$newFileName = date('dmYHis');
		move_uploaded_file($tmp_path, $directory . $newFileName . "." . $extension);

		$profilePhotoPath = "./View/assets/profile/" . $newFileName . "." . $extension;

		return $profilePhotoPath;
	}

	public function DeleteProfilePhoto($profilePhotoPath)
	{
		if (!file_exists($profilePhotoPath)) return;

		if (substr($profilePhotoPath, -11) == "default.jpg") return;

		unlink($profilePhotoPath);
	}

	private function ValidateImage($type, $size)
	{
		define("MB", 1000000);

		$validFormats = [
			"image/png",
			"image/jpg",
			"image/jpeg"
		];

		if (!in_array($type, $validFormats)) return false;

		if ($size >= 2 * MB) return false;

		return true;
	}
}