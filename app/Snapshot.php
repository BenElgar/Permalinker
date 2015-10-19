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

    public static function initItem($url)
    {
        $db = AWS::createClient('DynamoDb');

        $id = md5(uniqid($prefix='', $more_entropy=true));

        $creation_time = date('c');

        $data = [
            'id'          => ['S' => $id],
            'item_status'      => ['S' => 'pending'],
            'url'         => ['S' => $url],
            'modified_at' => ['S' => $creation_time],
            'created_at'  => ['S' => $creation_time],
        ];

        $db->putItem([
            'TableName' => self::table_name,
            'Item'      => $data,
        ]);

        return $id;
    }

    public static function fillItem($id, $root_path)
    {
        $db = AWS::createClient('DynamoDb');

        $data[':item_status'] = ['S' => 'ready'];
        $data[':modified_at'] = ['S' => date('c')];
        $data[':root_path']   = ['S' => $root_path];

        $result = $db->updateItem([
            'TableName' => self::table_name,
            'Key'       => [
                'id' => ['S' => $id],
            ],
            'ExpressionAttributeValues' => $data,
            'UpdateExpression' =>
                'SET
                    item_status = :item_status,
                    modified_at = :modified_at,
                    root_path        = :root_path
                    ',
            'ReturnValues' => 'ALL_NEW',
        ]);

        return $result;
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
