<?php namespace Keios\TransferToTools\CDR;
/**
 * TransferTo CDR Class
 */

use Illuminate\Config\Repository;
use Illuminate\Database\DatabaseManager;

/**
 * Class CallDetailRecord
 *
 * Provides simple fluent interface for TransferTo CDR
 *
 * @package Keios\TransferToTools\CDR
 *
 * How to use:
 *
 * $fields = [
 *  'call_start', 'called_number', 'caller_id', 'release_reason'
 * ];
 *
 * $clientId = int someId;
 *
 * $cdr = App::make('transferto.cdr');
 *
 * list($outboundFail, $queriesOF) = $cdr->setConnection($connDataArray)
 * ->forClient($clientId)
 * ->paginate(25, 1)
 * ->setCacheTime(false)
 * ->failed()
 * ->outbound()
 * ->fields($fields)
 * ->debug();
 */
class CallDetailRecord
{
    /**
     * Successful calls table
     * @var string
     */
    protected $callsTable = 'calls';

    /**
     * Failed calls table
     * @var string
     */
    protected $failedCallsTable = 'callsfailed';

    /**
     * Tariff names table
     * @var string
     */
    protected $tariffNamesTable = 'tariffsnames';

    /**
     * Stores Laravel's database manager instance
     * @var DatabaseManager
     */
    protected $db;

    /**
     * Stores Laravel's config repository instance
     * @var Repository
     */
    protected $config;

    /**
     * Stores database connection
     * @var null
     */
    protected $connection = null;

    /**
     * Lists client types with their database values
     * @var array
     */
    protected $clientTypes = [
        'wholesale' => 0,
        'pc2phone' => 1,
        'gkregistrar' => 2,
        'callback' => 4,
        'ivr' => 8,
        'callshop' => 16,
        'common' => 32,
        'reseller1' => 64,
        'reseller2' => 65,
        'reseller3' => 66,
        'pbx' => 128
    ];

    /**
     * Default client type
     * @var string
     */
    protected $defaultClientType = 'common';

    /**
     * Stores client type for this instance
     * @var
     */
    protected $clientType;

    /**
     * Stores Builder instance
     * @var null
     */
    protected $queryBuilder = null;

    /**
     * Default connection name
     * @var string
     */
    protected $connectionKey = 'keios_vps_cdr_database_connection';

    /**
     * Defines required connection parameters for validation
     * @var array
     */
    protected $requiredConnectionParameters = [
        'host', 'database', 'username', 'password'
    ];

    /**
     * Stores constant parameters for mysql connections
     * @var array
     */
    protected $constConnectionParameters = [
        'driver' => 'mysql',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => ''
    ];

    /**
     * Stores client id for this instance
     * @var null
     */
    protected $clientId = null;

    /**
     * Default records per page
     * @var int
     */
    protected $perPage = 25;

    /**
     * Default page
     * @var int
     */
    protected $page = 1;

    /**
     * Default cache time
     * @var int
     */
    protected $cacheTime = 5;

    /**
     * Stores table name used in this instance
     * @var null
     */
    protected $table = null;

    /**
     * Stores WHERE something = clauses
     * @var array
     */
    protected $wheres = [];

    /**
     * Stores WHERE something > clauses
     * @var array
     */
    protected $wheresMore = [];

    /**
     * Stores WHERE something < clauses
     * @var array
     */
    protected $wheresLess = [];

    /**
     * Stores WHERE IN clauses
     * @var array
     */
    protected $whereIns = [];

    /**
     * Stores WHERE OR clauses
     * @var array
     */
    protected $wheresOr = [];

    /**
     * Stores fields to fetch with this instance
     * @var array
     */
    protected $fields = ['*'];

    /**
     * Lists allowed fields from calls table
     * @var array
     */
    protected $allowedCallsFields = [
        'id_call',
        'id_client',
        'ip_number',
        'caller_id',
        'called_number',
        'call_start',
        'call_end',
        'route_type',
        'id_tariff',
        'cost',
        'duration',
        'tariff_prefix',
        'client_type',
        'id_route',
        'pdd',
        'costR1',
        'costR2',
        'costR3',
        'costD',
        'id_reseller',
        'tariffdesc',
        'id_cc',
        'ratio',
        'client_pdd',
        'orig_call_id',
        'term_call_id',
        'id_callback_call',
        'id_cn',
        'dialing_plan_prefix',
        'call_rate',
        'effective_duration',
        'dtmf',
        'call_data',
        'tariff_data',
        'id_dial_plan',
        'from_display_name',
    ];

    /**
     * Lists allowed fields from callsfailed table
     * @var array
     */
    protected $allowedFailedCallsFields = [
        'id_failed_call',
        'id_client',
        'ip_number',
        'caller_id',
        'called_number',
        'call_start',
        'route_type',
        'IE_error_number',
        'release_reason',
        'client_type',
        'id_route',
        'pdd',
        'type',
        'tariff_prefix',
        'id_tariff',
        'tariffdesc',
        'id_reseller',
        'orig_call_id',
        'term_call_id',
        'id_cc',
        'dialing_plan_prefix',
        'id_cn',
        'id_callback_call',
        'dtmf',
        'call_data',
        'id_dial_plan',
        'from_display_name',
    ];

    /**
     * Stores constant route type values
     * @var array
     */
    protected $routeTypes = [
        'outbound' => 0,
        'inbound' => 5,
    ];

    /**
     * Stores allowed fields for this instance
     * @var array
     */
    protected $allowedFields = [];

    /**
     * Flag: should we get tariff names via JOIN query?
     * @var bool
     */
    protected $withTariff = false;

    /**
     * Flag: is wholesale client?
     * @var bool
     */
    protected $isWholesale = false;

    /**
     * Stores gateway id for wholesale clients
     * @var
     */
    protected $gatewayId;

    /**
     * Build CDR class
     * @constructor
     * @param DatabaseManager $db
     * @param Repository $config
     */
    public function __construct(DatabaseManager $db, Repository $config)
    {
        $this->db = $db;
        $this->config = $config;
        $this->clientType = $this->clientTypes[$this->defaultClientType];
        $this->allowedFields[$this->failedCallsTable] = $this->allowedFailedCallsFields;
        $this->allowedFields[$this->callsTable] = $this->allowedCallsFields;
    }

    /**
     * Set connection to use by name or by connection configuration
     * @param string|array $connection
     * @throws \Exception
     * @return \Keios\TransferToTools\CDR\CallDetailRecord $this
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

        return $this;
    }

    /**
     * Sets client type for which we search in transferto's database
     * @param string $clientType
     * @throws \Exception
     * @return \Keios\TransferToTools\CDR\CallDetailRecord $this
     */
    public function forClientType($clientType)
    {
        if (!array_key_exists($clientType, $this->clientTypes))
            throw new \Exception('Voipswitch CDR error: unknown client type: ' . $clientType);

        $this->clientType = $this->clientTypes[$clientType];

        if ($this->clientType === 0)
            $this->isWholesale = true;

        return $this;
    }

    /**
     * Set client for which we retrieve cdr
     * @param int $id
     * @param int|bool $gatewayId
     * @return \Keios\TransferToTools\CDR\CallDetailRecord $this
     */
    public function forClient($id, $gatewayId = false)
    {
        $this->clientId = $id;
        $this->gatewayId = $gatewayId;

        return $this;
    }

    /**
     * Set caching time for queries
     * @param int $time
     * @return \Keios\TransferToTools\CDR\CallDetailRecord $this
     */
    public function setCacheTime($time = 5)
    {
        $this->cacheTime = $time;
        return $this;
    }

    /**
     * Search for tariff name
     * @return $this
     */
    public function withTariff()
    {
        $this->withTariff = true;
        return $this;
    }

    /**
     * Set pagination of results
     * @param int $perPage
     * @param int $page
     * @return \Keios\TransferToTools\CDR\CallDetailRecord $this
     * @throws \Exception
     */
    public function paginate($perPage = 25, $page = 1)
    {
        if ($perPage === 0)
            throw new \Exception('Voipswitch CDR error: invalid per page setting - cannot be 0!');

        if ($page < 1)
            throw new \Exception('Voipswitch CDR error: invalid page, can be only 1 and more');

        $this->perPage = $perPage;
        $this->page = $page;

        return $this;
    }

    /**
     * Select fields that should be retrieved
     * @param array $fields
     * @return \Keios\TransferToTools\CDR\CallDetailRecord $this
     * @throws \Exception
     */
    public function fields(array $fields)
    {
        if (is_null($this->table))
            throw new \Exception('Voipswitch CDR error: table has to be selected before selecting fields.');

        foreach ($fields as $requestedField)
            if (!in_array($requestedField, $this->allowedFields[$this->table]))
                throw new \Exception('Voipswitch CDR error: requested field name invalid: ' . $requestedField);

        $this->fields = $fields;

        return $this;
    }

    /**
     * Select callsfailed table
     * @return \Keios\TransferToTools\CDR\CallDetailRecord $this
     */
    public function failed()
    {
        $this->table = $this->failedCallsTable;
        return $this;
    }

    /**
     * Select calls table
     * @return \Keios\TransferToTools\CDR\CallDetailRecord $this
     */
    public function successful()
    {
        $this->table = $this->callsTable;
        return $this;
    }

    /**
     * Select only inbound calls
     * @return \Keios\TransferToTools\CDR\CallDetailRecord $this
     * @throws \Exception
     */
    public function inbound()
    {
        if ($this->isWholesale) {

            if (!$this->gatewayId)
                throw new \Exception('Gateway Id is not specified. You have to pass gateway Id for wholesale clients.');

            $this->wheres['route_type'] = $this->routeTypes['outbound']; // 0
            $this->wheres['id_route'] = $this->gatewayId;
        } else {
            $this->wheres['route_type'] = $this->routeTypes['inbound']; // 5
            $this->wheres['id_route'] = $this->clientId;
        }
        return $this;
    }

    /**
     * Select only outbound calls
     * @return \Keios\TransferToTools\CDR\CallDetailRecord $this
     */
    public function outbound()
    {
        $this->wheres['client_type'] = $this->clientType;
        $this->wheres['id_client'] = $this->clientId;
        $this->wheres['route_type'] = $this->routeTypes['outbound']; // always 0
        return $this;
    }

    /**
     * Select both inbound and outbound calls
     * @return \Keios\TransferToTools\CDR\CallDetailRecord $this
     * @throws \Exception
     */
    public function all()
    {
        if ($this->isWholesale && !$this->gatewayId)
            throw new \Exception('Gateway Id is not specified. You have to pass gateway Id for wholesale clients.');

        $this->wheresOr[] = [
            'where' => [ // outbound calls
                'route_type' => $this->routeTypes['outbound'],
                'id_client' => $this->clientId,
                'client_type' => $this->clientType
            ],
            'orWhere' => [ // inbound calls
                'route_type' => (($this->isWholesale) ? $this->routeTypes['outbound'] : $this->routeTypes['inbound']),
                'id_route' => (($this->isWholesale) ? $this->gatewayId : $this->clientId)
            ]
        ];

        return $this;
    }

    /**
     * Set search restriction on minimum date
     * @param string $date // ISODATE PREFERRED
     * @return \Keios\TransferToTools\CDR\CallDetailRecord $this
     */
    public function from($date)
    {
        $this->wheresMore['call_start'] = $date;
        return $this;
    }

    /**
     * Set search restriction on maximum date
     * @param string $date // ISODATE PREFERRED
     * @return \Keios\TransferToTools\CDR\CallDetailRecord $this
     */
    public function to($date)
    {
        $this->wheresLess['call_start'] = $date;
        return $this;
    }

    /**
     * Perform query with selected settings
     * @return array | \stdClass
     * @throws \Exception
     */
    public function fetch()
    {
        $this->check();

        $total = $this->getTotal($this->table);

        if ($total === 0) return [];

        $pages = ceil($total / $this->perPage);

        if ($this->page > $pages) $this->page = $pages;

        $offset = ($this->page - 1) * $this->perPage;

        $query = $this->queryBuilder
            ->table($this->table);

        if ($this->withTariff) {
            $query->leftJoin($this->tariffNamesTable, $this->table . '.id_tariff', '=', $this->tariffNamesTable . '.id_tariff');
            $this->fields = array_merge($this->fields, [$this->tariffNamesTable . '.description as tariffname']);
        }

        $query = $this->applyWheres($query);

        $result = $query->skip($offset)
            ->take($this->perPage)
            ->orderBy('call_start', 'desc')
            ->get($this->fields);

        return $result;
    }

    /**
     * Debug function, returns results and query log
     * @return array
     */
    public function debug()
    {
        $result = $this->fetch();
        $queries = $this->queryBuilder->getQueryLog();

        return [$result, $queries];
    }

    /**
     * Get total record count for query being built
     * @return int
     */
    protected function getTotal()
    {
        $query = $this->queryBuilder
            ->table($this->table);

        $query = $this->applyWheres($query);

        return $query->count();
    }

    /**
     * Apply wheres to query being built
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder $query
     */
    protected function applyWheres($query)
    {
        foreach ($this->wheresOr as $item) {
            foreach ($item as $whereClause => $arguments) {

                if ($whereClause === 'where') {

                    foreach ($arguments as $key => $value) {
                        $query->where($key, '=', $value);
                    }

                } else if ($whereClause === 'orWhere') {

                    $args = $arguments;
                    $firstArgument = each($args);
                    array_shift($args);

                    $query->orWhere($firstArgument['key'], '=', $firstArgument['value']);

                    foreach ($args as $key => $value)
                        $query->where($key, '=', $value);

                }
            }
        }

        foreach ($this->wheres as $key => $value) {
            $query->where($key, '=', $value);
        }

        foreach ($this->whereIns as $key => $valArray) {
            $query->whereIn($key, $valArray);
        }

        foreach ($this->wheresMore as $key => $value) {
            $query->where($key, '>', $value);
        }

        foreach ($this->wheresLess as $key => $value) {
            $query->where($key, '<', $value);
        }

        if ($this->cacheTime)
            $query->remember($this->cacheTime);

        return $query;
    }

    /**
     * Get connection to transferto database to operate on
     */
    protected function prepareQueryBuilder()
    {
        $this->queryBuilder = $this->db->connection($this->connection);
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
                throw new \Exception('Voipswitch CDR error: invalid connection data - missing parameter: ' . $requiredParameter);
        }
    }

    /**
     * Check if required data has been submitted
     * @throws \Exception
     */
    protected function check()
    {
        if (is_null($this->queryBuilder))
            throw new \Exception('Voipswitch CDR error: no connection selected!');
        if (is_null($this->clientId))
            throw new \Exception('Voipswitch CDR error: no client selected!');
        if (is_null($this->table))
            throw new \Exception('Voipswitch CDR error: no table selected!');

    }
} 