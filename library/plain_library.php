<?php
class PlainLibrary
{
    const rEarth = 6372795;
    
    public function __construct($string) {$this->string= json_decode($string,true);}
  
    public function getPartDistance($part)    
    {
        $json_a=$this->string;
        
        $lat1 = deg2rad($json_a['tr'][--$part][0]);
        $long1 = deg2rad($json_a['tr'][$part][1]);
        $lat2 = deg2rad($json_a['tr'][++$part][0]);
        $long2 = deg2rad($json_a['tr'][$part][1]);       
        
        $part_dist = (2*PlainLibrary::rEarth*asin(sqrt(pow(sin(($lat2-$lat1)/2),2)+cos($lat2)*cos($lat1)*pow(sin(($long2-$long1)/2),2))))/1000;
        return $part_dist;
    }  
    
    public function getDistance()    
    {
        $json_a=$this->string;
        $sum=0;
        
        $length=count($json_a['tr']);
        
        for ($i=1; $i<$length; $i++) 
        {
            $sum+=$this->getPartDistance($i); 
        }
        return $sum;
    }
    
    public function getPartTime($date, $part)  
    {
        $json_a=$this->string;
        $time=round(60*$this->getPartDistance($part)/$json_a['speed']);  
        return $time;
    }
    
    public function getPartTimeArrival($date, $part)  
    {   
        $sum=0;
        $length=$part+1;
        for ($i=1; $i<$length; $i++) 
        {
            $sum+=$this->getPartTime($date, $i); 
        }
        $date->add(new DateInterval('PT'.$sum.'M'));
        return $date;
    }    
    
    public function getTimeArrival($date)
    {
        $json_a=$this->string;
        $length=count($json_a['tr']);
        $sum=0;
        for ($i=1; $i<$length; $i++) 
        {
            $sum+=$this->getPartTime($date, $i);
        }
        $date->add(new DateInterval('PT'.$sum.'M'));
        return $date;
    } 
    
    public function Play()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
        $_SESSION['dep']= new DateTime();
    }     
    
    public function getPlace()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
        $json_a=$this->string;
        $_SESSION['now_t']= new DateTime();
        
        $diff = $_SESSION['now_t']->diff($_SESSION['dep']);
        
        $track = round(((1440*$diff->d+60*$diff->h+$diff->i+0.017*$diff->s)*$json_a['speed']/60),3);
        $percent = round((100*$track/$this->getDistance()),2);
        return $percent;
    }       
    
    
}