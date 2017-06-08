<?php

namespace Softremake;

/**
 * Class Path
 *
 * A variant of `scandir` PHP function using Generators and yield.
 * If it's a file, then `files()` method returns this file only.
 * Works with Linux forward slash file separator only.
 *
 * @package Softremake
 */
class Path {
	/**
	 * @var string
	 */
	protected $path;
	/**
	 * loaded in the constructor
	 * @var \SplFileInfo
	 */
	protected $lib;

	/**
	 * Path constructor.
	 *
	 * @param string $path
	 */
	function __construct(string $path)
	{
		$this->path = rtrim($path,'/'); //assure path has no end separator
		$this->lib = new \SplFileInfo($path);
	}

	/**
	 * Example: /foo/bar returns true
	 * @return bool
	 */
	function isDir() {
		return $this->lib->isDir();
	}

	/**
	 * Example: /foo/bar/some.php returns some.php
	 * @return string
	 */
	function name() {
		return $this->lib->getFilename();
	}

	/**
	 * Example: /foo/bar/some.php returns /foo/bar
	 * @return string
	 */
	function dir() {
		return $this->lib->getPath();
	}

	/**
	 * Example: /foo/bar/some.php returns .php
	 * @return string
	 */
	function ext() {
		return $this->lib->getExtension();
	}

	/**
	 * @return string
	 */
	function __toString()
	{
		return $this->path;
	}

	/**
	 * Creates a new Path instance for a subfolder or file in the current folder
	 * @param $name
	 *
	 * @return Path
	 */
	function subPath($name) {
		return new Path($this->path . '/' .$name);
	}

	/**
	 * If Path is a file, then `files()` method returns this file only.
	 * @return \Generator|void
	 */
	function files() {
		if( !$this->isDir() ) {
			yield $this;
			return;
		}

		$handle = opendir($this->path);
		while (($name = readdir($handle)) !== false) {
			if (($name == '.') || ($name == '..')) continue;

			$fullPath =  $this->subPath($name);
			foreach ($fullPath->files() as $child) {
				yield $child;
			}
		}
		closedir($handle);
	}

}