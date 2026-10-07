<?php
namespace myarcade\arcade\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
    protected $template;
    protected $user;
    protected $config;

    public function __construct(\phpbb\template\template $template, \phpbb\user $user, \phpbb\config\config $config)
    {
        $this->template = $template;
        $this->user = $user;
        $this->config = $config;
    }

    public static function getSubscribedEvents()
    {
        return [
            'core.user_setup' => 'load_language',
            'core.page_header' => 'add_arcade_link',
        ];
    }

    public function load_language($event)
    {
        $lang_set_ext = $event['lang_set_ext'];
        $lang_set_ext[] = [
            'ext_name' => 'myarcade/arcade',
            'lang_set' => 'common',
        ];
        $event['lang_set_ext'] = $lang_set_ext;
    }

    public function add_arcade_link()
    {
        $this->template->assign_var('U_ARCADE', append_sid($this->config['server_protocol'] . '://' . $this->config['server_name'] . '/arcade'));
    }
}
