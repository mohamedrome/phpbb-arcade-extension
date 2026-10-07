<?php
namespace myarcade\arcade\acp;

class main_info
{
    public function module()
    {
        return [
            'filename' => '\myarcade\arcade\acp\main_module',
            'title' => 'ACP_ARCADE_TITLE',
            'modes' => [
                'manage' => [
                    'title' => 'ACP_ARCADE_MANAGE',
                    'auth' => 'ext_myarcade/arcade && acl_a_board',
                    'cat' => ['ACP_ARCADE_TITLE'],
                ],
            ],
        ];
    }
}
