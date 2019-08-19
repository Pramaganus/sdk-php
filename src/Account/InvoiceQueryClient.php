<?php

namespace UKFast\SDK\Account;

use UKFast\SDK\Account\Entities\InvoiceQuery;
use UKFast\SDK\Client as BaseClient;
use UKFast\SDK\Page;
use UKFast\SDK\SelfResponse;

class InvoiceQueryClient extends BaseClient
{
    protected $basePath = 'account/';

    /**
     * Gets an individual invoice query
     *
     * @param string $id
     * @return InvoiceQuery
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getById($id)
    {
        $response = $this->request("GET", "v1/invoices/query/$id");
        $body = $this->decodeJson($response->getBody()->getContents());
        return new InvoiceQuery($body->data);
    }

    /**
     * @param $invoiceQuery
     * @return SelfResponse
     */
    public function create($invoiceQuery)
    {
        $response = $this->post("v1/invoices/query", $this->invoiceQueryToJson($invoiceQuery));
        $response = $this->decodeJson($response->getBody()->getContents());

        return (new SelfResponse($response))
            ->setClient($this)
            ->serializeWith(function ($response) {
                return $this->serializeInvoiceQuery($response->data);
            });
    }

    /**
     * Converts a response stdClass into an InvoiceQuery object
     *
     * @param \stdClass
     * @return \UKFast\SDK\Account\Entities\InvoiceQuery
     */
    protected function serializeInvoiceQuery($item)
    {
        $invoiceQuery = new Entities\InvoiceQuery($item);

        return $invoiceQuery;
    }

    protected function invoiceQueryToJson($invoiceQuery)
    {
        $payload = [
            'contact_id' => $invoiceQuery->contactId,
            'amount' => $invoiceQuery->amount,
            'what_was_expected' => $invoiceQuery->whatWasExpected,
            'what_was_received' => $invoiceQuery->whatWasReceived,
            'proposed_solution' => $invoiceQuery->proposedSolution,
            'invoice_ids' => $invoiceQuery->invoiceIds,
            'contact_method' => $invoiceQuery->contactMethod,
        ];

        return json_encode($payload);
    }
}