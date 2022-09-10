<?php
$soft = [[],[]]; // To-Do ALL CODES [[ GLOBAL VARIABLES  ][ EXECUTIONs ]]
$returnAddr = 0;
$stack = []; // To-do transfer to function 
$var = []; // To-do transfer to function local vars 


function store($i){
  global $var,$stack;
  $val = array_pop($stack);
  $var[$i] = $val;
}

function load($i){
  global $var, $stack;
  array_push($stack, $var[$i]);
}

function ise(){ // IS Equal
  global $var, $stack;
  if(count($stack) >=2){
    $b = array_pop($stack);
    $a = array_pop($stack);
    push((int)($a == $b));
  }else die("Error segmentation in ISE");
}

function isge(){ // Is greater or equal 
  global $var, $stack;
  if(count($stack) >=2){
    $b = array_pop($stack);
    $a = array_pop($stack);
    push((int)($a >= $b));
  }else die("Error segmentation in ISGE");
}

function isg(){ // is greater
  global $var, $stack;
  if(count($stack) >=2){
    $b = array_pop($stack);
    $a = array_pop($stack);
    push((int)($a > $b));
  }else die("Error segmentation in ISG");
}


function exec_code($line){ // VM-Stack based
  global $stack,$soft,$returnAddr,$var;
  for($i=0;$i<count($line);$i++)
  switch($line[$i]){
    case "PUSH": 
        array_push($stack, $line[++$i]);
      break;
    case "ADD": 
      if(count($stack) >=2){
        $pop2 = array_pop($stack);
        $pop1 = array_pop($stack);
        array_push($stack,$pop2+$pop1);
      }else die("Error segmentation in ADD");
      break;
    case "SUB": 
      if(count($stack) >=2){
        $pop2 = array_pop($stack);
        $pop1 = array_pop($stack);
        array_push($stack,$pop2-$pop1);
      }else die("Error segmentation in SUB");
      break;
    case "MUL": 
      if(count($stack) >=2){
        $pop2 = array_pop($stack);
        $pop1 = array_pop($stack);
        array_push($stack,$pop2*$pop1);
      }else die("Error segmentation in MUL");
      break;
    case "DIV": 
      if(count($stack) >=2){
        $pop2 = array_pop($stack);
        $pop1 = array_pop($stack);
        array_push($stack,$pop2/$pop1);
      }else die("Error segmentation in DIV");
      break;
    case "AND": 
      if(count($stack) >=2){
        $pop2 = (boolean)array_pop($stack);
        $pop1 = (boolean)array_pop($stack);
        push((int)($pop1 && $pop2));
      }else die("Error segmentation in AND");
      break;
    case "OR": 
      if(count($stack) >=2){
        $pop2 = (boolean)array_pop($stack);
        $pop1 = (boolean)array_pop($stack);
        push((int)($pop1 || $pop2));
      }else die("Error segmentation in OR");
      break;
      case "POP": 
        array_pop($stack);
      break;
      case "PRINT": 
        echo array_pop($stack);
      break;
      case "DUP": 
        $temp = array_pop($stack);
        array_push($stack, $temp);
        array_push($stack, $temp);
      break;
      case "STORE":
        store($line[++$i]);
        break;
      case "LOAD":
        load($line[++$i]);
        break;
      case "ISE":
        ise();
        break;
      case "ISG":
        isg();
        break;
      case "ISGE":
        isge();
        break;            
      case "JMP":
        $offset = $line[++$i];
        $i = $offset;
        break;
      case "JIT":
          $condition = array_pop($stack);
          $offset = $line[++$i];
          if($condition){
            $i = $offset;
          }
          break;
      case "JIF":
        $condition = array_pop($stack);
        $offset = $line[++$i];
        if(!$condition){
          $i = $offset;
        }
          break;
      case "GSTORE": 
        array_push($soft[0],  $line[++$i]);
        break;    
      case "GLOAD": 
        $GVar = $soft[0][$line[++$i]];
        array_push($soft[1], $GVar);
        break;
      case "CALL":
        $offset = $line[++$i];
        $returnAddr = $i;
        $i = $offset;
        break;
      case "RET":
        $i = $returnAddr;
        break;
      case "HALT":
        return;
        break;
    default: 
      die("Error : ".$line[$i]." Unknown !");
    break;
  }
}

$code = ["PUSH",16,"CALL",6,"ADD","PRINT","HALT","PUSH",4,"RET"]; // code 

exec_code($code); // vm s
//echo "VARS:"; var_dump($var);
//echo "STACKS:";var_dump($stack);
