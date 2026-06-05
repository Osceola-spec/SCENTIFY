<?php

use Illuminate\Support\Facades\Mail;

try {
    Mail::raw('This is a test email from Scentify', function ($message) {
        $message->to('jhidayat@student.ciputra.ac.id')
                ->subject('Test Email from Scentify Local');
    });
    echo "EMAIL SENT SUCCESSFULLY!\n";
} catch (\Exception $e) {
    echo "ERROR SENDING EMAIL:\n";
    echo $e->getMessage() . "\n";
}
