<p>Hello {{ $employee->first_name }},</p>

<p>Welcome to Standalone HRM. Your employee profile has been created.</p>

<p><strong>Login email:</strong> {{ $employee->email }}</p>
<p><strong>Temporary password:</strong> {{ $temporaryPassword }}</p>

<p>Please sign in and change your password after your first login.</p>

<p>Regards,<br>HR Team</p>