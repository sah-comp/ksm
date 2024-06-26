<?php
/**
 * KSM.
 *
 * @package KSM
 * @subpackage Controller
 * @author $Author$
 * @version $Id$
 */

/**
 * Filer controller.
 *
 * Document Management, Finder, File- and Directory Browser.
 *
 * @package KSM
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Filer extends Controller
{
    /**
     * Holds the records.
     *
     * @var array
     */
    public $records = [];

    /**
     * Holds the current record.
     *
     * @var RedBeanPHP\OODBBean
     */
    public $record = null;

    /**
     * Holds the default template.
     *
     * @var string
     */
    public $template = 'filer/index';

    /**
     * Constructor
     */
    public function __construct()
    {
        session_start();
        Auth::check();
        $this->record = R::dispense('file');
    }

    /*
     * Index.
     */
    public function index()
    {
        Permission::check(Flight::get('user'), 'filer', 'index');
        if (Flight::request()->data->submit == I18n::__('file_action_clone_from')) {
            $original = R::load('file', Flight::request()->data->clonefrom);
            $newname  = Flight::request()->data->clonename;
            $newpath  = str_replace($original->filename, $newname, $original->path);
            $newident = md5($newpath);
            if ( ! copy($original->path, $newpath)) {
                Flight::get('user')->notify(I18n::__('filer_error_clone'), 'error');
            } else {
                Flight::get('user')->notify(I18n::__('filer_success_clone', null, [$newname]), 'success');
            }
            $this->redirect('/filer/#file-' . $newident);
        }
        $this->records = [];
        $this->render();
    }

    /**
     * Loads the file bean identified by the ident and loads information about into the
     * sidebar area.
     *
     * @param string $ident the md5 key to retrieve a certain file
     * @return void
     */
    public function inspector($ident)
    {
        $this->record = R::findOne('file', " ident = ? LIMIT 1 ", [$ident]);
        Flight::render('filer/inspector', [
            'record'            => $this->record,
            'permission_delete' => Permission::validate(Flight::get('user'), 'filer', 'expunge'),
        ]);
        return;
        //echo 'Info about ' . $this->record->file;
    }

    /**
     * Edit a file.
     *
     * @param int $id of the slice
     */
    public function edit($id)
    {
        //session_start();
        //Auth::check();
        if (Flight::request()->method == 'POST') {
            try {
                $file = R::graph(Flight::request()->data->dialog, true);
                if (Flight::request()->data->delete) {
                    unlink($file->path);
                    R::trash($file);
                    echo ''; // clear the inspector areas
                    return;
                } else {
                    R::store($file);
                    Flight::render('filer/inspector', [
                        'record'            => $file,
                        'permission_delete' => Permission::check(Flight::get('user'), 'filer', 'expunge'),
                    ]);
                    return;
                }
            } catch (Exception $e) {
                error_log($e);
            }
        }
        $file = R::load('file', $id);
        Flight::render('filer/inspector', [
            'record'            => $file,
            'permission_delete' => Permission::check(Flight::get('user'), 'filer', 'expunge'),
        ]);
        return;
    }

    /**
     * Move a file to another folder.
     *
     * @param int $id of the file
     * @return void
     */
    public function move($id)
    {
        Permission::check(Flight::get('user'), 'filer', 'edit');
        $this->record = R::load('file', $id);
        $path         = Flight::request()->query->path;
        $newpath      = $path . '/' . $this->record->filename;
        //error_log('Move ' . $this->record->filename . ' to foldeer ' . $path);
        if ( ! rename($this->record->path, $newpath)) {
            Flight::get('user')->notify(I18n::__('filer_error_rename'), 'error');
        } else {
            Flight::get('user')->notify(I18n::__('filer_success_rename', null, [$this->record->filename]), 'success');
        }
        $this->redirect('/filer/index/#file-' . md5($newpath));
    }

    /**
     * Renders the filer page.
     */
    protected function render()
    {
        Flight::render('shared/notification', [], 'notification');
        //
        Flight::render('shared/navigation/account', [], 'navigation_account');
        Flight::render('shared/navigation/main', [], 'navigation_main');
        Flight::render('shared/navigation', [], 'navigation');
        Flight::render('filer/toolbar', [
            'record' => $this->record,
        ], 'toolbar');
        Flight::render('shared/header', [], 'header');
        Flight::render('shared/footer', [], 'footer');
        Flight::render($this->template, [
            'title'   => I18n::__("filer_head_title"),
            'record'  => $this->record,
            'records' => $this->records,
            'path'    => DMS_PATH,
        ], 'content');
        Flight::render('html5', [
            'title'    => I18n::__("filer_head_title"),
            'language' => Flight::get('language'),
        ]);
    }
}
