<?php namespace Keios\TransferToTools\Account;

use Illuminate\Config\Repository;
use Illuminate\Database\DatabaseManager;


/**
 * Class Manager
 * Class designed to perform non-api accessible operations on TransferTo database,
 * such as sip account creation, tariffs table modification and internal calls
 * setup in TransferTo.
 * @package Keios\TransferToTools\Account
 */
class Manager
{
    /**
     * Default connection name
     * @var string
     */
    protected $connectionKey = 'Keios_vps_cdr_database_connection';

    /**
     * Stores Query Builder instance connected to selected TransferTo database
     * @var null
     */
    protected $queryBuilder = null;

    /**
     * Defines required connection parameters for validation
     * @var array
     */
    protected $requiredConnectionParameters = ['host', 'database', 'username', 'password'];

    /**
     * Stores connection name
     * @var
     */
    protected $connection;

    /**
     * Stores basic connection parameters that all connections inherit
     * @var array
     */
    protected $constConnectionParameters = [
        'driver' => 'mysql',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => ''
    ];

    /**
     * Stores properties for methods of Manager Class, ie table name and required params for validation
     * @var array
     */
    protected $constDatabaseProperties = [
        'common' => [
            'table' => 'clientsshared',
            'requiredParams' => [
                'login' => [
                    'field' => 'login'
                ],
                'password' => [
                    'field' => 'password'
                ],
                'type' => [
                    'field' => 'type'
                ],
                'tariffId' => [
                    'field' => 'id_tariff'
                ],
                'currencyId' => [
                    'field' => 'id_currency'
                ],
                'techPrefix' => [
                    'field' => 'tech_prefix'
                ],
                'primaryCodec' => [
                    'field' => 'primary_codec'
                ],
                'codecs' => [
                    'field' => 'codecs'
                ]
            ]
        ],
        'tariff' => [
            'table' => 'tariffs',
            'requiredParams' => [
                'tariffId' => [
                    'field' => 'id_tariff'
                ],
                'didNumber' => [
                    'field' => 'prefix'
                ],
                'desc' => [
                    'field' => 'description'
                ]
            ],
            'optionalParams' => [
                'rate' => [
                    'field' => 'voice_rate',
                    'default' => '0.0000'
                ],
                'fromDay' => [
                    'field' => 'from_day',
                    'default' => 0
                ],
                'toDay' => [
                    'field' => 'to_day',
                    'default' => 6
                ],
                'fromHour' => [
                    'field' => 'from_hour',
                    'default' => 0
                ],
                'toHour' => [
                    'field' => 'to_hour',
                    'default' => 2400
                ]
            ]
        ],
        'dialplan' => [
            'table' => 'dialingplan',
            'requiredParams' => [
                'didNumber' => [
                    'field' => 'telephone_number'
                ],
                'routeId' => [
                    'field' => 'id_route'
                ],
                'callType' => [
                    'field' => 'call_type'
                ],
                'type' => [
                    'field' => 'type'
                ],
                'callLimit' => [
                    'field' => 'call_limit'
                ]
            ],
            'optionalParams' => [
                'priority' => [
                    'field' => 'priority',
                    'default' => 0
                ],
                'routeType' => [
                    'field' => 'route_type',
                    'default' => 5
                ],
                'balanceShare' => [
                    'field' => 'balance_share',
                    'default' => 100
                ],
                'dialPlanId' => [
                    'field' => 'id_dial_plan',
                    'default' => 1
                ],
                'fromDay' => [
                    'field' => 'from_day',
                    'default' => 0
                ],
                'toDay' => [
                    'field' => 'to_day',
                    'default' => 6
                ],
                'fromHour' => [
                    'field' => 'from_hour',
                    'default' => 0
                ],
                'toHour' => [
                    'field' => 'to_hour',
                    'default' => 2400
                ]
            ]
        ]
    ];

    /*
     * INSERT INTO dialingplan (telephone_number, priority, route_type,id_route,call_type,type,from_day,to_day,from_hour,to_hour,balance_share,call_limit,id_dial_plan)
     * VALUES ('{DIDNUMBER}',0,5,'{SIP_CLIENTID}',2290089984,0,0,6,0,2400,100,0,1);
     */

    /*
     * INSERT INTO tariffs (id_tariff, prefix, description,voice_rate,from_day,to_day,from_hour,to_hour)
     * VALUES ('{{ID_TARYFY}}', '{{DIDNUMBER}}', 'IPCall Internal', '0.0000',0,6,0,2400);
     */

    /*
     * INSERT INTO clientsshared (login, password, type, id_tariff, tech_prefix, codecs, primary_codec)
     * VALUES ('{SIP_LOGIN_STR}','{SIP_PASSWORD_STR}', 515, '{ID_TARYFY_INT}','{PREFIX_RULES}',{CODECS},{MAIN_CODEC});
     */

    /**
     * Build Manager object
     * @constructor
     * @param DatabaseManager $db
     * @param Repository $config
     */
    public function __construct(DatabaseManager $db, Repository $config)
    {
        $this->db = $db;
        $this->config = $config;
    }

    /**
     * Set connection to use by name or by connection configuration
     * Required args: ['host', 'database', 'username', 'password'] or connection name
     * @param string|array $connection
     * @throws \Exception
     */
    public function setConnection($connection)
    {
        if (is_array($connection)) {
            $this->validateConnection($connection);
            $this->config->set('database.connections.' . $this->connectionKey, array_merge($connection, $this->constConnectionParameters));
            $this->connection = $this->connectionKey;
        } else {
            // If passed string name of connection declared in config files
            $this->connection = $connection;
        }
        $this->prepareQueryBuilder();
    }

    /**
     * Get connection to TransferTo database to operate on
     */
    protected function prepareQueryBuilder()
    {
        $this->queryBuilder = $this->db->connection($this->connection);
    }

    /**
     * Creates a dial plan record for selected did number with selected parameters
     * Required args: ['didNumber', 'routeId', 'callType', 'type','callLimit']
     * Optional args: ['priority', 'routeType', 'balanceShare', 'dialPlanId', 'fromDay', 'toDay', 'fromHour', 'toHour']
     * @param array $arguments
     * @return int
     */
    public function createDialPlanRow(array $arguments)
    {
        $paramArray = $this->constDatabaseProperties['dialplan'];
        $this->validateArguments($arguments, $paramArray['requiredParams']);

        $insertData = [
            'telephone_number' => $arguments['didNumber'],
            'id_route' => $arguments['routeId'],
            'call_type' => $arguments['callType'],
            'type' => $arguments['type'],
            'call_limit' => $arguments['callLimit']
        ];

        $optionalArgs = $paramArray['optionalParams'];

        foreach ($optionalArgs as $optionalArgName => $optionalArgVals) {
            if (array_key_exists($optionalArgName, $arguments))
                $insertData[$optionalArgVals['field']] = $arguments[$optionalArgName];
            else
                $insertData[$optionalArgVals['field']] = $optionalArgVals['default'];
        }

        return $this->executeInsert($paramArray['table'], $insertData);
    }

    /**
     * Creates a tariff record for selected did number with selected parameters
     * Required args: ['tariffId' ,'didNumber', 'desc']
     * Optional args: ['rate', 'fromDay', 'toDay', 'fromHour', 'toHour']
     * @param array $arguments
     * @return int $id
     */
    public function createTariffRow(array $arguments)
    {
        $paramArray = $this->constDatabaseProperties['tariff'];
        $this->validateArguments($arguments, $paramArray['requiredParams']);

        $insertData = [
            'id_tariff' => $arguments['tariffId'],
            'prefix' => $arguments['didNumber'],
            'description' => $arguments['desc']
        ];

        $optionalArgs = $paramArray['optionalParams'];

        foreach ($optionalArgs as $optionalArgName => $optionalArgVals) {
            if (array_key_exists($optionalArgName, $arguments))
                $insertData[$optionalArgVals['field']] = $arguments[$optionalArgName];
            else
                $insertData[$optionalArgVals['field']] = $optionalArgVals['default'];
        }

        return $this->executeInsert($paramArray['table'], $insertData);
    }

    /**
     * Create Common (Retail) VPS Client, returns id_client
     * Required args: ['login', 'password', 'type', 'tariffId', 'techPrefix', 'primaryCodec', 'codecs']
     * @param array $arguments
     * @throws \InvalidArgumentException
     * @return int $id
     */
    public function createCommon(array $arguments)
    {
        $paramArray = $this->constDatabaseProperties['common'];
        $this->validateArguments($arguments, $paramArray['requiredParams']);

        $insertData = [
            'login' => $arguments['login'],
            'password' => $arguments['password'],
            'type' => $arguments['type'],
            'id_tariff' => $arguments['tariffId'],
            'tech_prefix' => $arguments['techPrefix'],
            'codecs' => $arguments['codecs'],
            'primary_codec' => $arguments['primaryCodec'],
            'id_currency' => $arguments['currencyId']
        ];

        return $this->executeInsert($paramArray['table'], $insertData);
    }

    /**
     * Executes insert query
     * @param string $table
     * @param array $data
     * @return int
     */
    protected function executeInsert($table, array $data)
    {
        return $this->queryBuilder
            ->table($table)
            ->insertGetId($data);
    }

    /**
     * Check if connection array is valid for Laravel's ConnectionFactory
     * @param array $connection
     * @throws \Exception
     */
    protected function validateConnection(array $connection)
    {
        foreach ($this->requiredConnectionParameters as $requiredParameter) {
            if (!array_key_exists($requiredParameter, $connection))
                throw new \Exception('TransferTo CDR error: invalid connection data - missing parameter: ' . $requiredParameter);
        }
    }

    /**
     * Validates arguments for class methods
     * @param array $arguments
     * @param array $required
     */
    protected function validateArguments(array $arguments, array $required)
    {
        foreach ($required as $requiredArgumentName => $requiredArgumentProperties)
            if (!array_key_exists($requiredArgumentName, $arguments)) {
                $e = new \Exception();
                $trace = $e->getTrace();
                $method = $trace[1];
                throw new \InvalidArgumentException('Missing argument ' . $requiredArgumentName . ' for method ' . $method);
            }
    }

} 