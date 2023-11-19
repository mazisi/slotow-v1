<?php
/**
 * Check network connection.
 */
if(! function_exists('checkNetworkStatus')){
   function checkNetworkStatus(){
    switch (connection_status()){
      case CONNECTION_NORMAL:
        $connection_status = 1;
        break;
      case CONNECTION_ABORTED:
        $connection_status = 'Connection Aborted';
        break;
      case CONNECTION_TIMEOUT:
        $connection_status = 'Connection timed out';
        break;
      case (CONNECTION_ABORTED & CONNECTION_TIMEOUT):
        $connection_status = 'Connection aborted and timed out';
        break;
      default:
        $connection_status = 'Unknown';
        break;
      }
      
      return $connection_status;
  }
}
?>