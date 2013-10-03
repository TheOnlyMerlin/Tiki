<?php
/**
 * Run phpunit tests, and report only those tests that STARTED failing
 * or were fixed since the last baseline run.
 */

//error_reporting(0);

set_include_path(get_include_path() . PATH_SEPARATOR . '../..');

require_once('lib/debug/Tracer.php');


$tracer->traces_are_on = true;
$tracer->trace_file_path = 'traces-run-phpunit.txt';
$tracer->tiki_trace_active_ids =
    array(
        'print_diffs_with_baseline',
        'config_from_cmdline_options',
        'TestRunnerWithBaseline',
        'TestRunnerWithBaseline.run',
        'run_tests',
        'run_phpunit',
        'validate_cmdline_options',
        "TestRunnerWithBaseline.compare_two_test_runs",
    );

$tracer->clear_trace_file();

require_once('lib/test/TestRunnerWithBaseline.php');


$runner = new TestRunnerWithBaseline();
$runner->run();

