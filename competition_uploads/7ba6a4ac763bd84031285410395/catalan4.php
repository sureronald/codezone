<?php
$fp=fopen("test.c","r");
$data=fread($fp,filesize("test.c"));
fclose($fp);

$zp=gzopen("notthere.zip","w");
gzwrite($zp,$data);
gzclose($zp);
$z=new zipArchive;
print_r($z);
?>