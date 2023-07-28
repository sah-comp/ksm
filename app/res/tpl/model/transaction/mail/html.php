<?php echo Flight::textile(vsprintf($record->getContracttype()->emailtext, [
    $record->getContracttype()->name,
    $record->number,
    $record->localizedDate('bookingdate'),
    $user->email,
    $user->name
]));

Flight::render('shared/mail/signature', [
    'company' => $company,
    'user' => $user
]);
