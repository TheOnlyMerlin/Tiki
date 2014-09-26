<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

namespace Tracker\Tabular\Writer;

class CsvWriter
{
	private $schema;
	private $file;

	function __construct(\Tracker\Tabular\Schema $schema, $outputFile)
	{
		$this->schema = $schema;
		$this->file = new \SplFileObject($outputFile, 'w');
	}

	function sendHeaders()
	{
	}

	function write(\Tracker\Tabular\Source\SourceInterface $source)
	{
		$columns = $this->schema->getColumns();
		$headers = [];
		foreach ($columns as $column) {
			$headers[] = $column->getEncodedHeader();
		}
		$this->file->fputcsv($headers);

		foreach ($source->getEntries() as $entry) {
			$row = [];

			foreach ($columns as $column) {
				$row[] = $entry->render($column);
			}
			
			$this->file->fputcsv($row);
		}
	}
}

