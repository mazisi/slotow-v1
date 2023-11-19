<?php

/**
 * Check if file has been saved in blob storage.
 */
if (! function_exists('fileExist')) {
  function fileExist($remote_file){
    // Initialize cURL
      $ch = curl_init($remote_file);
      curl_setopt($ch, CURLOPT_NOBODY, true);
      curl_exec($ch);
      $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);                
          
return $responseCode == 200;

}
}
  

?>