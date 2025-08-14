<?php


namespace App\Http\Services;

class MailScript{

    public function send_mail()
    {
        # code...
        $to='germanuskeming@gmail.com'; 
        //$to="nishang80@gmail.com";
        // Sender 
        $from = 'momo@stlouis-group.org'; 
        $fromName = 'St. Louis Application Portal'; 
         
        // Email subject 
        $subject = 'St Louis Student Application Form';  
         
        // Attachment file 
        // $file = "../../pdf/student_application_form_".$your_id.".pdf"; 
         
        // Email body content 
        $htmlContent = ' 
            <h3>Student Application Form</h3> 
            <p>Thank you for trusting us. Attached  below is a copy of your admission form , please download , print and submit to the registry as soon as possible along site your documents
            <br>This system is powered by NISHANG SYSTEMS PLC  
            
            </p> 
        '; 
         
        // Header for sender info 
        $headers = "From: $fromName"." <".$from.">"; 
         
        // Boundary  
        $semi_rand = md5(time());  
        $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
         
        // Headers for attachment  
        $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 
         
        // Multipart boundary  
        $message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" . 
        "Content-Transfer-Encoding: 7bit\n\n" . $htmlContent . "\n\n";  
         
        // Preparing attachment 
        if(!empty($file) > 0){ 
            if(is_file($file)){ 
                $message .= "--{$mime_boundary}\n"; 
                $fp =    @fopen($file,"rb"); 
                $data =  @fread($fp,filesize($file)); 
         
                @fclose($fp); 
                $data = chunk_split(base64_encode($data)); 
                $message .= "Content-Type: application/octet-stream; name=\"".basename($file)."\"\n" .  
                "Content-Description: ".basename($file)."\n" . 
                "Content-Disposition: attachment;\n" . " filename=\"".basename($file)."\"; size=".filesize($file).";\n" .  
                "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n"; 
                
                $message .= "--{$mime_boundary}--"; 
                $returnpath = "-f" . $from; 
                 
                // Send email 
                $mail = mail($to, $subject, $message, $headers, $returnpath);  
                 
                // Email sending status 
                echo $mail?"<h1>Email Sent Successfully!</h1>":"<h1>Email sending failed.</h1>"; 
            }else{
                echo "<h1>PDF file not found !</h1>";
            } 
        } 
    }
}
 

?>