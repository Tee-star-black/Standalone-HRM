<?php

namespace App\Mail;

use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmployeeWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Employee $employee,
        public string $temporaryPassword
    ) {}

    public function build(): self
    {
        return $this->subject('Welcome to MedConnect HRM')
            ->view('emails.employee-welcome');
    }
}