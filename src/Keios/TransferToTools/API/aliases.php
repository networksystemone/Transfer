<?php
/**
 * Provides method aliases for easy access to API Commands
 */

return [
    'performApiTest' => 'ApiTest',
    'getAdminInfo' => 'AdminLogon',
    'getClientInfo' => 'ClientLogon',
    'getDids' => 'GetClientDids',
    'getProfile' => 'GetClientPersonal',
    'updateProfile' => 'UpdateClientPersonal',
    'changePassword' => 'ChangePassword',
    'getVoiceMails' => 'GetVoiceMails',
    'addPayment' => 'AdminPaymentAdd',
    'getAnsweringRules' => 'GetAnsweringRules',
    'updateAnsweringRule' => 'UpdateAnsweringRule',
    'createAnsweringRule' => 'UpdateAnsweringRule',
    'deleteAnsweringRule' => 'DeleteAnsweringRule',
    'addAuthorizedAni' => 'ClientAniUpdate',
    'updateAuthorizedAni' => 'ClientAniUpdate',
    'deleteAuthorizedAni' => 'ClientAniDelete',
    'getAuthorizedAni' => 'GetAniNumbers',
    'getSpeedDials' => 'ClientSpeedDialList',
    'addSpeedDial' => 'ClientSpeedDialUpdate',
    'updateSpeedDial' => 'ClientSpeedDialUpdate',
    'deleteSpeedDial' => 'ClientSpeedDialDelete',
    'addContact' => 'ClientContactsUpdate',
    'updateContact' => 'ClientContactsUpdate',
    'deleteContact' => 'ClientContactsDelete',
    'getContacts' => 'ClientContactsGet',
    'getContactGroups' => 'ClientContactsGroupGet',
    'addContactGroup' => 'ClientContactsGroupUpdate',
    'updateContactGroup' => 'ClientContactsGroupUpdate',
    'deleteContactGroup' => 'ClientContactsGroupDelete',
    'getCallerId' => 'ClientCallerIdGet',
    'setCallerId' => 'ClientCallerIdSet',
];