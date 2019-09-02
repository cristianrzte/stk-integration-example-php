<?php
require_once('models/class.php');


class Report extends DbModel {




/**
     * @param $event name of event
     * Gets the id of the event.
     * The id is searched by name
**/
  public function GetEventId($event =''){
    if($event != '' || !empty($event)){
      $this->query = "
      SELECT evento_id
      FROM table_events
      WHERE evento_nombre ='$event'
      ";
      $this->get_results_from_query();
    }

    if(count($this->rows) > 0 ){
      foreach ($this->rows[1] as $evento => $valor) {
        $this->$evento = $valor;
      }

      return $this->$evento;

    }else{

      return $mensaje = 'No se encontro el evento, o no existe. <br> por favor verifique el nombre del mismo'. var_dump($this->rows[1]) .'<br>';

    }
  }




    /**
        * Get one by one the different reports sent by a real gps / gsm device and add them to an array
        * If you can not find events, return an error message
     **/
   public function GetEventOnDb(){

     $this->query = "
     SELECT *
     FROM test_report
     ORDER BY report_id ASC
     LIMIT 1
     ";

     $this->get_results_from_query();
     if(count($this->rows) == 1){
       foreach ($this->rows as $Reporte => $DataReport) {
         $this->$Reporte = $DataReport;
       }
       return $this->$Reporte;
     }else{
       return $mensaje = 'No se encontraron eventos';
     }
   }




   /**
      * Try to return an approximate value in degrees to represent the course of the device
      * The value is searched in an array
    */
   public function CreateSimpleHeading($breading=''){

     $Heading = array(
      '0'   => 'N',
      '45'  => 'NE',
      '90'  => 'E',
      '135' => 'SE',
      '180' => 'S',
      '225' => 'SO',
      '270' => 'O',
      '315' => 'NO'

    );

    return array_search($breading, $Heading); 

   }

   /**
      * @param $report_id id of event
      * Remove an event that has been saved  
    */
   public function DeleteReport($report_id=''){
     $this->query = "
     DELETE FROM test_report
     WHERE report_id = '$report_id' ";
     $this->execute_single_query();
   }


   /**
    * Build an array with data obtained from the GetEventOnDb () & GetEventId () methods
    * Send the data to the function that will later make the http request
    */

   public function NewReport(){

     $Reporte = $this->GetEventOnDb();
     $EventID = $this->GetEventId($Reporte['reportType']);
     $ClientData = new SitrackExternalReportTest();
     $report = array(
     			      'loginCode'=> $Reporte['equipo_id'],
                'reportDate'=> str_replace('+00:00','Z',$ClientData::$SimpleDateFormat),
                 'reportType'=> $EventID,
                 'latitude'=>  $latitud = floatval($Reporte['Latitude']) ,
                 'longitude'=> $longitud = floatval($Reporte['Longitude']) ,
                 'gpsDop'=> $gpsDop = floatval($Reporte['gpsDop']),
                 'gpsSatellites'=> $gps = floatval($Reporte['GpsSatellites']),
                 'heading' => $heading = $this->CreateSimpleHeading($Reporte['heading']),
                 'altitudeLabel' => 'GPS',
                 'speed'=> $speed = floatval($Reporte['Speed']),
                 'speedLabel' => 'GPS',
     		);
        $ClientData->CreateReport($report, $Reporte['report_id']);
   }
}













 ?>
