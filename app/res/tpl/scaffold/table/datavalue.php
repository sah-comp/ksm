<?php
/**
 * Cinnebar.
 *
 * @package Cinnebar
 * @subpackage Template
 * @author $Author$
 * @version $Id$
 */
?>
<?php
if (isset($attribute['prefix'])):
    echo $record->{$attribute['prefix']['callback']['name']}($attribute['name']);
endif;
if (isset($attribute['callback'])):
    //error_log('Attr ' . $attribute['callback']['name']);
    echo htmlspecialchars($record->{$attribute['callback']['name']}($attribute['name']) ?? '');
else:
    echo htmlspecialchars($record->{$attribute['name']} ?? '');
endif;
?>
