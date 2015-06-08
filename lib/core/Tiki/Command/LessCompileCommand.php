<?php
// (c) Copyright 2002-2015 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id: CacheClearCommand.php 50250 2014-03-07 14:39:32Z lphuberdeau $

namespace Tiki\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LessCompileCommand  extends Command
{
	protected function configure()
	{
		$this
			->setName('less:compile')
			->setDescription('Compile LESS theme files into CSS')
			->addArgument(
				'location',
				InputArgument::OPTIONAL,
				'Location of less files to compile (themes)',
				'themes'
			)
			->addOption(
				'all',
				null,
				InputOption::VALUE_NONE,
				'Build all less files, including all built-in "Tiki" themes'
			)
			->addOption(
				'only',
				null,
				InputOption::VALUE_OPTIONAL,
				'Only compile named theme or themes, separated by commas'
			)
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$all = $input->getOption('all');
		$only = array_filter(explode(',', $input->getOption('only')));
		if (empty($only)) {
			$all = true;
		}
		$type = $input->getArgument('location');

		$cachelib = \TikiLib::lib('cache');

		switch ($type)
		{
			case 'themes':
				$output->writeln('Compiling less files from themes');
				foreach (new \DirectoryIterator('themes') as $fileInfo)
				{
					if ($fileInfo->isDot() || ! $fileInfo->isDir()) {
						continue;
					}
					$dirname = $fileInfo->getFilename();
                    if (!empty($only) && ! in_array($dirname, $only) && ! $all) {
                        continue;
                    }
					if ($dirname === 'base_files') {
						$less_file = "themes/$dirname/less/tiki_base.less";
					} else {
						$less_file = "themes/$dirname/less/$dirname.less";
					}
                    if (! file_exists($less_file)) {
                        continue;
                    }
                    $css_file = "themes/$dirname/css/$dirname.css";
                    if (file_exists($css_file && filemtime($css_file) >= filemtime($less_file))) {
                        continue;
                    }
					$command = "vendor/bin/lessc $less_file $css_file";
					$output->writeln($command);
					$result = shell_exec($command);
                    $result = str_replace(array("\r", "\n"), '', $result);
					$output->writeln($result);
				}
				break;
			case '':
				return $output->writeln('Missing parameter.');
			default:
				$output->writeln('<error>Invalid location for less files requested.</error>');
		}

		$output->writeln('Clearing all caches');
		$cachelib->empty_cache();
	}
}
