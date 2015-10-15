<?php

namespace App;

use AWS;

class Snapshot
{
    const table_name = 'snapshots';

    public static function createTable()
    {
        $db = AWS::createClient('DynamoDb');
        $db->createTable([
            'TableName' => self::table_name,
            'AttributeDefinitions' => [
                [
                    'AttributeName' => 'id',
                    'AttributeType' => 'S',
                ],
            ],
            'KeySchema' => [
                [
                    'AttributeName' => 'id',
                    'KeyType'       => 'HASH',
                ],
            ],
            'ProvisionedThroughput' => [
                'ReadCapacityUnits'  => 10,
                'WriteCapacityUnits' => 20,
            ],
        ]);

        $db->waitUntil('TableExists', [
            'TableName' => self::table_name,
        ]);
    }

    public static function dropTable()
    {
        $db = AWS::createClient('DynamoDb');
        $db->deleteTable([
            'TableName' => self::table_name,
        ]);
    }

    public static function put(array $data)
    {
        $db = AWS::createClient('DynamoDb');

        $id = md5(uniqid($prefix='', $more_entropy=true));

        $data['id']         = ['S' => $id];
        $data['created_at'] = ['S' => date('c')];

        $db->putItem([
            'TableName' => self::table_name,
            'Item'      => $data,
        ]);

        echo($id);
    }

    public static function find($id)
    {
        $db = AWS::createClient('DynamoDb');

        return $db->getItem([
            'ConsistentRead' => true,
            'TableName' => self::table_name,
            'Key' => [
                'id' => ['S' => $id]
            ],
        ]);
    }

}
