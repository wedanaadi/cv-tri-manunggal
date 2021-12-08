<?php
defined('BASEPATH') or die('No direct script access allowed!');

function bulatkan($bil)
{
  $bilbulat = floor($bil);
  $bulsubstr = substr($bilbulat, -3);
  return (int)$bilbulat - (int)$bulsubstr;
}
