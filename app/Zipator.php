<?php

namespace Zipator;


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
	public function zip($inputDir, $outputDir = NULL, $maxSize = NULL, $fileExtension = NULL)
	{
		$size = 0;
		$count = 1;
		$atLeastOne = FALSE;
		$outputDir = $outputDir !== NULL ? $outputDir : $inputDir;

		$zip = self::createArchive($outputDir, $count);

		/** @var \SplFileInfo $fileinfo */
		foreach (new \FilesystemIterator($inputDir) as $fileinfo) {

			if($fileinfo->isDir() || strcasecmp($fileinfo->getExtension(), 'zip') === 0)
				continue;

			if ($fileExtension) {
				if (strcasecmp($fileinfo->getExtension(), $fileExtension) === 0) {
					$atLeastOne = TRUE;
					$zip->addFile($fileinfo->getPathname(), $fileinfo->getFilename());
				} else {
					continue;
				}
			} else {
				$atLeastOne = TRUE;
				$zip->addFile($fileinfo->getPathname(), $fileinfo->getFilename());
			}
			$size += $fileinfo->getSize();

			if ($maxSize !== NULL && $size >= $maxSize) {
				$zip->close();
				$count++;
				$size = 0;
				$zip = self::createArchive($outputDir, $count);
			}
		}

		$zip->close();

		return $atLeastOne ? $count : 0;
	}

	/**
	 * @param string $dir
	 * @param int $count
	 * @return ZipArchive
	 */
	private function createArchive($dir, $count)
	{
		$zip = new ZipArchive();
		$name = $dir . '/' . self::$archiveName . $count . '.zip';

		if ($zip->open($name, ZipArchive::CREATE) !== TRUE) {
			exit("Cannot open <$name>\n");
		}

		return $zip;
	}
}