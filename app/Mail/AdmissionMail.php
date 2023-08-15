<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdmissionMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $name;
    public $campus;
    public $program;
    public $matric;
    public $fee1_dateline;
    public $fee2_dateline;
    public $help_email;
    public $director_name;
    public $dean_name;
    public function __construct($name, $campus,$program, $matric, $fee1_dateline, $fee2_dateline, $help_email, $director_name, $dean_name)
    {
        $this->name = $name;
        $this->campus = $campus;
        $this->program = $program;
        $this->matric = $matric;
        $this->fee1_dateline = $fee1_dateline;
        $this->fee2_dateline = $fee2_dateline;
        $this->help_email = $help_email;
        $this->director_name = $director_name;
        $this->dean_name = $dean_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.student.admission_letter');
    }
}
