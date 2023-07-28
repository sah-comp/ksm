<?php
if ($record->mailbody == '') :
    echo Flight::textile(vsprintf($record->getContracttype()->emailtext, [
        $record->getContracttype()->name,
        $record->number,
        $record->localizedDate('bookingdate'),
        $user->email,
        $user->name
    ]));
else :
    echo Flight::textile($record->mailbody);
endif;

Flight::render('shared/mail/signature', [
    'company' => $company,
    'user' => $user
]);
