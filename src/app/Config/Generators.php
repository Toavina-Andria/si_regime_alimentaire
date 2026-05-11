<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Generators extends BaseConfig
{

    public array $views = [
        'make:cell' => [
            'class' => 'CodeIgniter\Commands\Generators\Views\cell.tpl.php',
            'view'  => 'CodeIgniter\Commands\Generators\Views\cell_view.tpl.php',
        ],
        'make:command'      => 'CodeIgniter\Commands\Generators\Views\command.tpl.php',
        'make:config'       => 'CodeIgniter\Commands\Generators\Views\config.tpl.php',
        'make:controller'   => 'CodeIgniter\Commands\Generators\Views\controller.tpl.php',
        'make:entity'       => 'CodeIgniter\Commands\Generators\Views\entity.tpl.php',
        'make:filter'       => 'CodeIgniter\Commands\Generators\Views\filter.tpl.php',
        'make:migration'    => 'CodeIgniter\Commands\Generators\Views\migration.tpl.php',
        'make:model'        => 'CodeIgniter\Commands\Generators\Views\model.tpl.php',
        'make:seeder'       => 'CodeIgniter\Commands\Generators\Views\seeder.tpl.php',
        'make:validation'   => 'CodeIgniter\Commands\Generators\Views\validation.tpl.php',
        'session:migration' => 'CodeIgniter\Commands\Generators\Views\migration.tpl.php',
    ];
}
