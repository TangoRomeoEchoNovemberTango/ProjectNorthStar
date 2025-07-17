<?php
return [
  'twitter' => [
    'consumer_key'    => getenv('TW_CONS_KEY'),
    'consumer_secret' => getenv('TW_CONS_SECRET'),
    'access_token'    => getenv('TW_ACCESS_TOKEN'),
    'access_secret'   => getenv('TW_ACCESS_SECRET'),
  ],
  'facebook' => [
    'page_id'           => getenv('FB_PAGE_ID'),
    'page_access_token' => getenv('FB_PAGE_TOKEN'),
  ],
];
