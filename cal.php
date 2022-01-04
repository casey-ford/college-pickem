<?php
 
// helper function to convert hex value to string
function hex2str($hex) {
    for($i=0;$i<strlen($hex);$i+=2) $str .= chr(hexdec(substr($hex,$i,2)));
    return $str;
}
 
// show appointments as easy to read HTML
echo "<h1>Appointments</h1>";
 
if (($handle = fopen("calendaritem.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000000, ",")) !== FALSE) {
 
            // get the ServerIcal column
            $ical = $data[17];
 
            // strip out all '00' and ' ' strings from the output
            $ical = str_replace(" 00 ","",$ical);
            $ical = str_replace(" ","",$ical);
 
            // convert to string
            $line = hex2str($ical);
 
            // tidy up HTML to make it easily human readable
            $line = substr($line,strpos($line,"DTSTART"));
            $line = substr($line,0,strpos($line,"UID"));
            $line = str_replace("SUMMARY:","SUMMARY: <strong>",$line);
            $line = str_replace(PHP_EOL,"</strong>" .PHP_EOL,$line);
            $line = str_replace("DTSTART;VALUE=DATE:","DTSTART;VALUE=DATE: <strong>",$line);
            $line = str_replace(" DTEND;","</strong>  DTEND;",$line);
            $line = str_replace("DTEND;VALUE=DATE:","DTEND;VALUE=DATE: <strong>",$line);
            $line = str_replace("  SUMMARY: ","</strong>   SUMMARY: ",$line);
            $line = str_replace("DTSTART;VALUE=DATE:","Start Date:",$line);
            $line = str_replace("DTEND;VALUE=DATE:","End Date:",$line);
 
            // output as HTML
            echo "<br/>$line";
        }
 
    fclose($handle);
}
 
// show raw appointment data
echo "<h1>Raw Data</h1>";
 
if (($handle = fopen("calendaritem.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000000, ",")) !== FALSE) {
 
            // get the ServerIcal column
            $ical = $data[17];
 
            // strip out all '00' and ' ' strings from the output
            $ical = str_replace(" 00 ","",$ical);
            $ical = str_replace(" ","",$ical);
 
            // convert to string
            $line = hex2str($ical);
 
            // output as HTML
            echo "<br/><br/>$line";
        }
 
    fclose($handle);
}
 
?>