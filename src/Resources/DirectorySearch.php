<?php


namespace Northwestern\SysDev\DynamicForms\Resources;


class DirectorySearch implements ResourceInterface
{

    public static function indexName(): string
    {
        return "DirectorySearch";
    }

    public static function components(): string
    {
        return '[
    {
      "label": "uid",
      "tableView": true,
      "key": "uid",
      "type": "textfield",
      "input": true
    },
    {
      "label": "mail",
      "tableView": true,
      "key": "email",
      "type": "email",
      "input": true
    },
    {
      "label": "displayName",
      "tableView": true,
      "multiple": true,
      "key": "displayName",
      "type": "textfield",
      "input": true
    },
    {
      "label": "nuAllTitle",
      "tableView": true,
      "multiple": true,
      "key": "textField",
      "type": "textfield",
      "input": true
    }
  ]';
    }
}