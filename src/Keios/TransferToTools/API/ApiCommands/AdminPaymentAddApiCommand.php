<?php namespace Keios\TransferToTools\API\ApiCommands;

use Keios\TransferToTools\API\ApiCommand;
use Keios\TransferToTools\API\Contracts\ApiCommandInterface;
use InvalidArgumentException;

class AdminPaymentAddApiCommand extends ApiCommand implements ApiCommandInterface
{

    protected $apiSubUrl = 'json/syncreply/admin.payment.add';

    protected $method = 'POST';

    protected $paymentTypes = [
        'PrePaid', 'PostPaid', 'PrePaidReturn', 'PostPaidReturn'
    ];

    protected $requiredArguments = ['money', 'paymentType', 'idClient', 'clientType', 'addToInvoice', 'description'];

    public function getBody()
    {
        $requestBody = [];

        foreach ($this->requiredArguments as $argument) {
            if ($argument === 'paymentType')
                if (!in_array($this->arguments['paymentType'], $this->paymentTypes))
                    throw new InvalidArgumentException('Invalid payment type: ' . $this->arguments['paymentType']);
            $requestBody[$argument] = $this->arguments[$argument];
        }

        return json_encode($requestBody);
    }
}
