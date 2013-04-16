<?php

/**

 * @version $Id: wbty_jplayer.php 2011-01-25 12:41:40 svn $

 * @package    Wbty_jplayer

 * @subpackage Base

 * @author     EasyJoomla {@link http://www.easy-joomla.org Easy-Joomla.org}

 * @author     David Fritsch {@link fritschservices.com}

 * @author     Created on 27-May-2011

 * @license    GNU/GPL

 */





//-- No direct access

defined('_JEXEC') or die('=;)');



/**

 * Wbty_jplayer Table class

 *

 * @package    Wbty_jplayer

 * @subpackage Tables

 */

class TableWbty_jplayer extends JTable

{

    /**

     * Primary Key

     *

     * @var int

     */

    var $id = null;



    /**

     * @var string

     */

    var $title = null;

	

	/**

     * @var string

     */

    var $artist = null;

	

	/**

     * @var string

     */

    var $album = null;

	

	/**

     * @var tinyint

     */

    var $state = null;

	

	/**

     * @var int

     */

    var $ordering = null;

	

	/**

     * @var string

     */

    var $location = null;

	

	/**

     * @var string

     */

    var $image = null;



    /**

     * Constructor

     *

     * @param object $db Database connector object

     */

    function TableWbty_jplayer(& $db)

    {

        parent::__construct('#__wbty_jplayer', 'id', $db);

    }//function



}//class

