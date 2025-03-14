<?php

namespace App\processor;

use App\client\ClientInterface;
use App\helpers\ArrayHelper;

class IncrementalTicketProcessor implements ProcessorInterface
{
    private ClientInterface $client;
    private ?string $nextPage = null;
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function hasNotFetchedData(): bool
    {
        return (bool)$this->nextPage;
    }

    public function getDataToExport(): array
    {
        if ($this->nextPage) {
            $data = $this->client->fetchNextPage($this->nextPage);
        } else {
            $data = $this->client->fetchIncrementalTickets();
        }


        $usersMapById = ArrayHelper::arrayMapByField($data['users']);
        $organizationsMapById = ArrayHelper::arrayMapByField($data['organizations']);
        $groupsMapById = ArrayHelper::arrayMapByField($data['groups']);

        $ticketList = [];

        foreach ($data['tickets'] as $ticket) {
            $ticketList[] = [
                'ID' => $ticket['id'],
                'Description' => $ticket['description'],
                'Status' => $ticket['status'],
                'Priority' => $ticket['priority'],
                'Agent ID' => $ticket['assignee_id'],
                'Agent Name' => $usersMapById[$ticket['assignee_id']]['name'],
                'Agent Email' => $usersMapById[$ticket['assignee_id']]['email'],
                'Contact ID' => $ticket['requester_id'],
                'Contact Name' => $usersMapById[$ticket['requester_id']]['name'],
                'Contact Email' => $usersMapById[$ticket['requester_id']]['email'],
                'Group ID' => $ticket['group_id'],
                'Group Name' => $groupsMapById[$ticket['group_id']]['name'],
                'Company ID' => $ticket['organization_id'],
                'Company Name' => $organizationsMapById[$ticket['organization_id']]['name'],
                'Comments' => $ticket['comment_count']
            ];
        }

        $this->nextPage = isset($data['next_page']) ? $data['next_page'] : null;

        return $ticketList;
    }
}