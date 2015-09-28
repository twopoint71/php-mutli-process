<?php
/*
 * date: 27-sep-2015
 * auth: robert smith
 * info: run a php daemon process
 * lics: MIT License (see LICENSE.txt for details)
 */

$pwd = realpath("");

$daemon = array(
  "log"      => $pwd."/service.log",
  "errorLog" => $pwd."/service.error.log",
  "pid_file" => $pwd."/",
  "pid"      => "",
  "stdout"   => NULL,
  "stderr"   => NULL,
  "callback" => array("myProcessA", "myProcessB")
  );

/*
 * main (spawn new process)
 */
foreach ($daemon["callback"] as $k => &$v)
  {
  $pid = pcntl_fork();

  if ($pid < 0)
    exit("fork failed: unable to fork\n");

  if ($pid == 0)
    spawnChores($daemon, $v);
  }

exit("fork succeeded, spawning process\n");
/*
 * end main
 */

/*
 * functions
 */
function spawnChores(&$daemon, &$callback)
  {
  // become own session
  $sid = posix_setsid();

  if ($sid < 0)
    exit("fork failed: unable to become a session leader\n");

  // set working directory as root (so files & dirs are not locked because of process)
  chdir("/");

  // close open parent file descriptors system STDIN, STDOUT, STDERR
  fclose(STDIN);
  fclose(STDOUT);
  fclose(STDERR);

  // setup custom file descriptors
  $daemon["stdout"] = fopen($daemon["log"], "ab");
  $daemon["stderr"] = fopen($daemon["errorLog"], "ab");

  // publish pid
  $daemon["pid"] = sprintf("%d", getmypid());
  file_put_contents($daemon["pid_file"].$callback.".pid", $daemon["pid"]."\n");

  // publish start message to log
  fprintf($daemon["stdout"], "%s daemon %s started with pid %s\n", date("Y-M-d H:i:s"), $callback, $daemon["pid"]);

  call_user_func($callback, $daemon);

  // publish finish message to log
  fprintf($daemon["stdout"], "%s daemon %s terminated with pid %s\n", date("Y-M-d H:i:s"), $callback, $daemon["pid"]);

  exit(0);
  }

function myProcessA(&$daemon)
  {
  $run_for_seconds = 30;
  for($i=0; $i<$run_for_seconds; $i++)
    {
    fprintf($daemon["stdout"], "Just being a process, %s, for %d more seconds\n", __FUNCTION__, $run_for_seconds - $i);
    sleep(1);
    }
  }

function myProcessB(&$daemon)
  {
  $run_for_seconds = 30;
  for($i=0; $i<$run_for_seconds; $i++)
    {
    fprintf($daemon["stdout"], "Just being a process, %s, for %d / %d seconds\n", __FUNCTION__, $i, $run_for_seconds);
    sleep(1);
    }
  }
?>

