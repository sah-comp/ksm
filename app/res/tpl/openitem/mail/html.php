<?php
echo Flight::textile(vsprintf($company->dunningemailtext, [
    $record->getDunning()->name,
    $record->localizedDate('dunningprintedon'),
    $user->email,
    $user->name
]));

Flight::render('shared/mail/signature', [
    'company' => $company,
    'user' => $user
]);
