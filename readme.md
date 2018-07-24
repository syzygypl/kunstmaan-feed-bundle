Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
$ composer require syzygypl/kunstmaan-feed-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new  SZG\KunstmaanFeedBundle\KunstmaanFeedBundle(),
        );

        // ...
    }

    // ...
}
```


Usage
=====

`article` below represents an indexed type. It may be define as search_type in page configuration.

``` yml
kunstmaan_node:
    pages:
        'AppBundle\Entity\Pages\ArticlePage':
            ...
            indexable: true
            search_type: article
```
            
### TWIG

##### Most recent 100 articles:
```jinja
{# Short notation, use defaults #}
{% set articles = get_article_feed() %}

{# Simple notation #}
{% set articles = get_recent_article_feed({ limit: 100 }) %}

{# full notation #}
{% set articles = get_feed_items('article', { limit: 100, feed: 'recent' }) %}
```

##### Random product:
```jinja
{% set random_product = get_random_product_feed({ limit: 1 }) %}
```

### PHP :

##### Most recent 100 articles:
```php
<?php
// ...

$articles = $this->get('szg_feed.elastic_search_items_provider')->getFeedItems('article', [
    'feed' => 'recent',
    'limit' => 100
]);

```

##### Random product:
```php
<?php
// ...

$articles = $this->get('szg_feed.elastic_search_items_provider')->getFeedItems('product', [
    'feed' => 'random',
    'limit' => 1
]);

```

Options reference
=================

| Option        | Type                                      | Description                                                              | Default       |
| ------------: |-------------------------------------------|--------------------------------------------------------------------------|---------------|
| page          | int                                       | Current page number                                                      | 1             |
| limit         | int                                       | Items per page                                                           | 100           |
| category      | HasNodeInterface<br>Category              | Page children<br>Category children                                       | null          |       
| tags          | string<br>array<string><br>Taggable       | Single tag<br>Tags collection<br>Object implements Taggable interface    | null          |
| tags_logic    | 'any'<br>'all'<br>'few'                   | at least one must fit<br> all must fit<br>33% must fit                   | 'any'         |    
| excluded      | HasNodeInterface<br>Node<br>NodeTranslation<br>Category<br>Result<br>string *NodeId*<br>int *NodeId*<string>         | Array of elements to exclude from results. | null          |       
| feed          | 'recent'<br>'random'<br><...custom_feed>  | Feed alias                                                               | 'recent'      |
| returnRawResultSet | bool                                 | Return raw result set                                                    | false         |
| extra         | mixed                                     | You can pass any data to RelationDefinition and use it for custom query  | null          |


Defining custom feed types
==========================

### Sort single field custom feed:

Configure custom feeds in config.yml
```yml
kunstmaan_feed:
    custom: 
        title : 'desc'
            
```

### Advanced custom feed:

#### 1. Implement FeedElasticSearchInterface

Implement `SZG\KunstmaanFeedBundle\Feed\ElasticSearch\Interfaces\FeedElasticSearchInterface`.

    
```php
<?php
// ...
class TitleDesc extends FeedElasticSearchInterface
{

   /**
    * @param QueryDefinition $queryDefinition
    */
   public function modifyQuery(QueryDefinition $queryDefinition)
   {
        $queryDefinition->getQuery()->addSort(['title' => ['order' => 'desc']]);
   }

}
```
    
####  2. Register custom feed service:
```yml
szg_feed.feed.title:
    class: AppBundle\Feed\TitleDesc
    tags:
      - { name: szg_feed.feed, alias: title }
```

### Use custom feed

```jinja
{% set articlesByTitle = get_title_article_feed() %}
```
