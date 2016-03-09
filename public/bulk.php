<?php
/**
 * Cinnebar.
 *
 * @package Cinnebar
 * @subpackage System
 * @author $Author$
 * @version $Id$
 */

/**
 * RedbeanPHP Version 3.5.
*/
require __DIR__ . '/../lib/redbean/rb.php';

/**
 * Autoloader.
*/
require __DIR__ . '/../vendor/autoload.php';

/**
 * Configuration.
*/
require __DIR__ . '/../app/config/config.php';

// Send bulk mail
$bulks = R::find('bulk', ' newsletter_id IS NOT NULL AND send = 0 LIMIT 100');
foreach ($bulks as $id => $bulk) {

    $mail = new PHPMailer();
    $mail->Charset = 'UTF-8';
    $mail->Subject = utf8_decode($bulk->newsletter->name);
    $mail->From = $bulk->newsletter->replytoemail;
    $mail->FromName = utf8_decode($bulk->newsletter->replytoname);
    $mail->AddReplyTo($bulk->newsletter->replytoemail, utf8_decode($bulk->newsletter->replytoname));
    
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPKeepAlive = true;
    $mail->Host = $bulk->newsletter->mailserver->host;
    $mail->Port = $bulk->newsletter->mailserver->port;
    $mail->Username = $bulk->newsletter->mailserver->user;
    $mail->Password = $bulk->newsletter->mailserver->pw;
    
    $result = true;
    $body_html = $bulk->newsletter->template->html;
    $body_text = $bulk->newsletter->template->txt;
    $mail->MsgHTML($body_html);
    $mail->AltBody = $body_text;
    $mail->ClearAddresses();
    $mail->AddAddress($bulk->email->email);
    $bulk->send = $mail->Send();
    echo $bulk->email->email . "\n";
    R::store($bulk);
}
