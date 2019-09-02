<?php
require_once('models/class.php');


class SitrackExternalReportTest{


  private static  $APPLICATION_ID = "";
  private static  $SECRET_KEY =  "";
  static  $SimpleDateFormat;



  function __construct(){

     date_default_timezone_set('UTC');
     self::$SimpleDateFormat  = date(DATE_ATOM);

  }


  /**
   * Create the signature to be used in authentication with the sitrack platform
   * @param $report array with the information received by the device that will be sent to the sitrack platform
   * @param $reporte_id identifier of the information that will be sent
   */


  function CreateReport($report = array(),  $reporte_id = ''){
      $data = json_encode($report);
      $timestamp = time();
      $Signature = base64_encode(md5(utf8_decode(self::$APPLICATION_ID.self::$SECRET_KEY.$timestamp),true));
      $AuthParams = 'application="'.self::$APPLICATION_ID.'",signature="'.$Signature.'",timestamp="'.$timestamp.'"';
      
      $headers = array('Content-Type:application/json; charset=utf-8','Authorization: SWSAuth '. $AuthParams);


      $Request = $this->httpRequest("PUT", "http://any-url.com/port/frame/", $data,$headers);
      
      $ModelReport = new Report();
      if($Request == 200){
        $ModelReport->DeleteReport($reporte_id);
        echo 'Received';
      }elseif ($Request == 500) {
        echo 'Reintentando...<br>';
        sleep(60);
        $ModelReport->NewReport();
      }elseif ($Request >= 400) {
      // Save Logs
      }else{
        // Save Logs
      }

  }

  /**
   * Performs a request through HTTP protocol.
   * @param $method HTTP method (GET, POST, PUT, DELETE).
   * @param $url The Uniform Resource Locator http: // [domain | IP: port] / [web / context] / [ResourceName]? [{param = value [&]} *].
   * @param $data the content of the HTTP packet. 
   * @param $headers parameters of Authorization HTTP header, separated by comma or space,each parameter can be just a value or a ordered pair name = "value".
   * @param $response Shows data obtained if it is accessed manually.
   * @return The content of the HTTP response packet.
   */

function httpRequest($method = '' , $url = '', $data = array(), $headers = array() ) {

    $curl = CURL_INIT();
    CURL_SETOPT_ARRAY($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 120,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => $method,
    CURLOPT_POSTFIELDS => $data,
    CURLOPT_HTTPHEADER =>$headers

  ));



  $response = CURL_EXEC($curl);

  if (!CURL_ERRNO($curl)) {
    $http_code = CURL_GETINFO($curl, CURLINFO_HTTP_CODE);
    return $http_code;
  }

  $err = CURL_ERROR($curl);
  CURL_CLOSE($curl);

  if ($err) {
    echo "Error # :" . $err;
      print_r($data);
      echo'<br>';
      print_r($headers);
  }else{
    print_r($response);
  }

}


}





 ?>
