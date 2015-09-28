<h2>PHP Daemon Spawning Script</h2>

<h3>Easy to use multi-process spawning script for PHP</h3>
<p>I wrote this for someone on stackoverflow. Throwing it up on github also seemed like a good idea too.</p>
<p>For more widespread compatibility, I did not include the capability to set the process name. However, Process Control Extensions will need to be enabled on the target PHP installation.</p>
<p>A word of caution: <a href="http://software-gunslinger.tumblr.com/post/47131406821/php-is-meant-to-die">PHP was meant to die.</a> Meaning, the language was mean to execute for a few seconds then exit. Though, garbage cleanup in PHP has come a long way, be careful. Monitor processes for unexpected memory consumption, or other oddities. Watch everything like a hawk for a while before "set it and forget it" phases, and even then, still check the processes once in a while or have them automatically notify if something becomes amiss.</p>

<h3>Licensing</h3>
<p>The MIT License.  See LICENSE.txt for details</p>

