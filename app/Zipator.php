<?php

namespace Zipator;


use Nette\Utils\Strings;
use ZipArchive;

class Zipator
{
	/** @var string */
	public static $archiveName = 'archive_';


	/**
	 * @param string $inputDir - path to input directory
	 * @param string $outputDir - path to output directory
	 * @param int $maxSize - max archive size in bytes
	 * @param string $fileExtension - archive only files with this extension
	 * @return int - number of created archives
	 */
	public function zip($inputDir, $outputDir = null, $maxSize = null, $fileExtension = null)
	{
		$size = 0;
		$count = 1;
		$atLeastOne = false;
		$outputDir = $outputDir !== null ? $outputDir : $inputDir;
		$names = [];

		list($zip, $name) = self::createArchive($outputDir, $count);
		$names[] = $name;

		/** @var \SplFileInfo $fileinfo */
		foreach (new \FilesystemIterator($inputDir) as $fileinfo) {

			if($fileinfo->isDir() || in_array(Strings::lower($fileinfo->getBasename()), $names, true)){
				continue;
			}

			if ($fileExtension) {
				if (strcasecmp($fileinfo->getExtension(), $fileExtension) === 0) {
					$atLeastOne = true;
					$zip->addFile($fileinfo->getPathname(), $fileinfo->getFilename());
				} else {
					continue;
				}
			} else {
				$atLeastOne = true;
				$zip->addFile($fileinfo->getPathname(), $fileinfo->getBasename());
			}
			$size += $fileinfo->getSize();

			if ($maxSize !== null && $size >= $maxSize) {
				$zip->close();
				$count++;
				$size = 0;
				list($zip, $name) = self::createArchive($outputDir, $count);
				$names[] = $name;
			}
		}

		$zip->close();

		return $atLeastOne ? $count : 0;
	}

	/**
	 * @param string $dir
	 * @param int $count
	 * @return array
	 */
	private function createArchive($dir, $count)
	{
		$zip = new ZipArchive();
		$name = $dir . '/' . self::$archiveName . $count . '.zip';

		if ($zip->open($name, ZipArchive::CREATE) !== true) {
			exit("Cannot open <$name>\n");
		}

		return [$zip, $name];
	}
}