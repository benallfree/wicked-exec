<?

class ExecMixin extends Mixin
{
  static function interpolate()
  {
    $args = func_get_args();
    $s = array_shift($args);
    foreach($args as $arg)
    {
      $s = preg_replace_callback("/([!?])/", function($matches) use ($arg) {
        if(count($matches)<=1) return;
        switch($matches[1])
        {
          case '?':
            return escapeshellarg($arg);
            break;
          case '!':
            return $arg;
            break;
          default:
            W::error("Unknown type $type in interpolate");
        }
        
      }, $s, 1);
    }
    return $s;
  }
  
  static function cmd($cmd)
  {
    $args = func_get_args();
    $s = call_user_func_array('interpolate', $args);
    puts($s);
    exec($s . " 2>&1",$output, $result);
    if($result!=0)
    {
      puts("Error: $result");
      puts($s);
      puts($output);
      W::error("Execution failed.");
    }
    return $output;
  }

  static function exec($cmd, $expected_retval=0, &$out='')
  {
    exec($cmd . " 2>&1", $out, $ret);
    if ($ret != $expected_retval)
    {
      W::error("Command failed: $cmd", array($ret, $out));
    }
    return $ret;
    
  }
}