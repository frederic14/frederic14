<?php
/**
*
* @version $Id: formulaire de presentation ,v 1.0.0 01/03/2016  ErnadoO Exp $
* @copyright (c) 2008 ErnadoO
* @modifier par frederic14 pour phpbb 3.1
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace frederic14\presentation\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
/**
* Assign functions defined in this class to event listeners in the core
*
* @return array
* @static
* @access public
*/
    static public function getSubscribedEvents()
    {
        return array(
            'core.user_setup'                        => 'load_language_on_setup',
			'core.modify_posting_parameters'          => 'load_presentationform',
        );
    }
    
    public function load_language_on_setup($event)
    {
        $lang_set_ext = $event['lang_set_ext'];
        $lang_set_ext[] = array(
            'ext_name' => 'frederic14/presentation',
            'lang_set' => 'presentation_form',
        );
        $event['lang_set_ext'] = $lang_set_ext;
    }
}