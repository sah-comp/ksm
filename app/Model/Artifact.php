<?php
/**
 * Cinnebar.
 *
 * My lightweight no-framework framework written in PHP.
 *
 * @package Cinnebar
 * @author $Author$
 * @version $Id$
 */

/**
 * Artifact model.
 *
 * @package Cinnebar
 * @subpackage Model
 * @version $Id$
 */
class Model_Artifact extends Model
{
    /**
     * update.
     *
     * This will check for file uploads.
     */
    public function update()
    {
        parent::update();
    }

    /**
     * after_delete.
     *
     * After the bean was deleted from the database, we will also delete the real file.
     *
     */
    public function after_delete()
    {
        if (is_file(Flight::get('upload_dir').'/'.$this->bean->filename)) {
            unlink(Flight::get('upload_dir').'/'.$this->bean->filename);
        }
    }
}
