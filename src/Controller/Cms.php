<?php
/**
 * Cinnebar.
 *
 * @package Cinnebar
 * @subpackage Controller
 * @author $Author$
 * @version $Id$
 */

/**
 * Cms controller.
 *
 * @package Cinnebar
 * @subpackage Controller
 * @version $Id$
 */
class Controller_Cms extends Controller
{
    /**
     * Holds a flag to decide wether to trigger a click-simulation on meta tab or not.
     *
     * @var bool 
     */
    public $trigger_meta = false;
    
    /**
     * Renders a domain when it has pages.
     *
     * @uses Model_Domain::getContent()
     *
     * @param RedBean_OODBean $domain to render
     */
    public function frontend(RedBean_OODBBean $domain)
    {
        if ( ! $template_data = $domain->getContent(Flight::get('language'))) return false;
        Flight::lastModified($domain->lastmodified);
        Flight::render($template_data['mytemplate'], $template_data);
        Flight::stop();
    }

    /**
     * Displays the cms index page.
     *
     * Domains that are direct children of the sitesfolder are by definition sites.
     * The index page offers the option to fill in a form to add a new site. When a site
     * was added successfully a notification is given and the client will be redirected
     * to that new node.
     */
    public function index()
    {
        session_start();
        Auth::check();
        Permission::check(Flight::get('user'), 'cms', 'index');
		// Pick up the pieces
		Flight::render('shared/notification', array(), 'notification');
        Flight::render('shared/navigation/account', array(), 'navigation_account');
        Flight::render('shared/navigation/main', array(), 'navigation_main');
        Flight::render('shared/navigation', array(), 'navigation');
		Flight::render('shared/header', array(), 'header');
		Flight::render('shared/footer', array(), 'footer');
        Flight::render('cms/toolbar', array(), 'toolbar');
        Flight::render('cms/index', array(
            'sitemap' => Flight::sitesfolder()
                                ->hierMenu('/cms/node/', Flight::get('user')->getLanguage(), true, 'id')
                                ->render(array('class' => 'sitemap-navigation clearfix'))
        ), 'content');
		// Use a layout to pack it all
        Flight::render('html5', array(
            'title' => I18n::__('admin_head_title'),
            'language' => Flight::get('language')
        ));
    }
    
    /**
     * Edit a node.
     *
     * @param int $id of the domain (node)
     * @param int (optional) $id of the page to load. If not set the first page is loaded
     */
    public function node($id, $page_id = null)
    {
        @session_start();
        Auth::check();
        $domain = R::load('domain', $id);
        //$root = $domain->getRoot($stop_at = Flight::sitesfolder()->getId());
        //Permission::check(Flight::get('user'), $domain, 'edit');
		// Pick up the pieces
		//echo 'You want to edit node '.$id;
		$pages = $domain->getPages(Flight::get('user')->getLanguage(), true);
		if (empty($pages)) {
		    $page = R::dispense('page');
		}
		else {
		    if ($page_id && isset($pages[$page_id])) {
		        $page = $pages[$page_id];
		    }
		    else {
		        $page = reset($pages);
		    }
		}
		Flight::render('cms/container/addpage', array(
		    'domain' => $domain
		), 'form_addpage');
		Flight::render('cms/container/pages', array(
		    'page' => $page,
		    'pages' => $pages
		), 'pages');
		Flight::render('cms/container/meta', array(
		    'page' => $page
		), 'page_meta');
		Flight::render('cms/container/page', array(
		    'trigger_meta' => $this->trigger_meta,
		    'page' => $page
		), 'page');
		Flight::render('cms/container/content', array());
    }
    
    /**
     * Edit a page.
     *
     * @param int $id of the page
     */
    public function page($id)
    {
        session_start();
        Auth::check();
        $page = R::load('page', $id);
		Flight::render('cms/container/meta', array(
		    'page' => $page
		), 'page_meta');
		Flight::render('cms/container/page', array(
		    'trigger_meta' => $this->trigger_meta,
		    'page' => $page
		));
    }
    
    /**
     * Update a page meta information on a POST request.
     *
     * @param int $id of the page
     */
    public function meta($id)
    {
        session_start();
        Auth::check();
        try {
            $page = R::graph(Flight::request()->data->dialog, true);
            if (Flight::request()->data->delete) {
                R::trash($page);
                return $this->node($page->domain_id);
            }
            else {
                R::store($page);
                return $this->node($page->domain_id, $page->getId());
            }
        }
        catch (Exception $e) {
            error_log($e);
        }
    }
    
    /**
     * Edit a slice.
     *
     * @param int $id of the slice
     */
    public function slice($id)
    {
        session_start();
        Auth::check();
        if (Flight::request()->method == 'POST') {
            try {
                $slice = R::graph(Flight::request()->data->dialog, true);
                if (Flight::request()->data->delete) {
                    R::trash($slice);
                    echo '';
                    return;
                }
                else {
                    R::store($slice);
                    echo $slice->render('frontend');
                    return;
                }
            }
            catch (Exception $e) {
                error_log($e);
            }
        }
        $slice = R::load('slice', $id);
		Flight::render("module/{$slice->module}/backend", array(
		    'record' => $slice
		), 'form_details');
		Flight::render('module/form', array());
    }
    
    /**
     * Add a new item on a POST request.
     *
     * @param string $type
     */
    public function add($type)
    {
        session_start();
        Auth::check();
        return $this->{'add_'.$type}();
    }

    /**
     * Add a new page.
     */
    protected function add_page()
    {
        try {
            $record = R::graph(Flight::request()->data->dialog, true);
            R::store($record);
            $this->trigger_meta = true;
            return $this->node($record->domain_id, $record->getId());
        }
        catch (Exception $e) {
            error_log($e);
        }
    }
    
    /**
     * Add a new slice.
     */
    protected function add_slice()
    {
        try {
            $record = R::graph(Flight::request()->data->dialog, true);
            R::store($record);
    		Flight::render("module/{$record->module}/backend", array(
    		    'record' => $record
    		), 'form_details');
    		Flight::render('module/form', array(), 'form');
    		Flight::render('cms/container/slice', array());
        }
        catch (Exception $e) {
            error_log($e);
        }
    }
}
