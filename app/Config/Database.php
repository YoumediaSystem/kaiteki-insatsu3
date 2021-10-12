<?php

namespace Config;

use CodeIgniter\Database\Config;

/**
 * Database Configuration
 */
class Database extends Config
{
    /**
     * The directory that holds the Migrations
     * and Seeds directories.
     *
     * @var string
     */
    public $filesPath = APPPATH . 'Database' . DIRECTORY_SEPARATOR;

    /**
     * Lets you choose which connection group to
     * use if no other is specified.
     *
     * @var string
     */
    public $defaultGroup = 'default';

    /**
     * The default database connection.
     *
     * @var array
     */
    public $default = [
        'DSN'      => '',
        'hostname' => 'mysql108.xbiz.ne.jp',
        'username' => 'kaitekihonya_prt',
        'password' => 'GBD9Skf97kmkKze6',
        'database' => 'kaitekihonya_print',
        'DBDriver' => 'MySQLi',
        'DBPrefix' => '',
        'pConnect' => false,
        'DBDebug'  => (ENVIRONMENT !== 'production'),
        'charset'  => 'utf8',
        'DBCollat' => 'utf8_general_ci',
        'swapPre'  => '',
        'encrypt'  => false,
        'compress' => false,
        'strictOn' => false,
        'failover' => [],
        'port'     => 3306,
    ];

    /**
     * This database connection is used when
     * running PHPUnit database tests.
     *
     * @var array
     */
    public $tests = [
        'DSN'      => '',
        'hostname' => 'sv94.xserver.jp',
        'username' => 'xsvx2010092_kth3',
        'password' => 'KaitekiHonya3',
        'database' => 'xsvx2010092_printtest',
        'DBDriver' => 'MySQLi',
//		'DBPrefix' => 'db_',  // Needed to ensure we're working correctly with prefixes live. DO NOT REMOVE FOR CI DEVS
        'DBPrefix' => '',  // Needed to ensure we're working correctly with prefixes live. DO NOT REMOVE FOR CI DEVS

        'pConnect' => false,
        'DBDebug'  => (ENVIRONMENT !== 'production'),
        'charset'  => 'utf8',
        'DBCollat' => 'utf8_general_ci',
        'swapPre'  => '',
        'encrypt'  => false,
        'compress' => false,
        'strictOn' => false,
        'failover' => [],
        'port'     => 3306,
    ];

    /**
     * This database connection is used when
     * running PHPUnit database tests.
     *
     * @var array
     */
    public $local = [
        'DSN'      => '',
        'hostname' => '127.0.0.1',
        'username' => 'root',
        'password' => '',
        'database' => 'print_local',
        'DBDriver' => 'MySQLi',
//		'DBPrefix' => 'db_',  // Needed to ensure we're working correctly with prefixes live. DO NOT REMOVE FOR CI DEVS

        'DBPrefix' => '',  // Needed to ensure we're working correctly with prefixes live. DO NOT REMOVE FOR CI DEVS
        
        'pConnect' => false,
        'DBDebug'  => (ENVIRONMENT !== 'production'),
        'charset'  => 'utf8',
        'DBCollat' => 'utf8_general_ci',
        'swapPre'  => '',
        'encrypt'  => false,
        'compress' => false,
        'strictOn' => false,
        'failover' => [],
        'port'     => 3306,
    ];

    //--------------------------------------------------------------------

    public function __construct()
    {
        parent::__construct();

        // Ensure that we always set the database group to 'tests' if
        // we are currently running an automated test suite, so that
        // we don't overwrite live data on accident.

        if (ENVIRONMENT === 'development'
        &&  strpos($_SERVER['SERVER_NAME'], 'xsvx2010092.xsrv.jp') !== false
        ){
            $this->defaultGroup = 'tests';
        }

        elseif (ENVIRONMENT === 'development'
//        ||  strpos($_SERVER['SERVER_NAME'], 'print.l') !== false
        ){
            $this->defaultGroup = 'local';
        }

    }

    //--------------------------------------------------------------------

}
