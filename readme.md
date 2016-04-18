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


Step 3: Usage
-------------

### TWIG

##### Most recent 100 articles:
```jinja
{# Short notation, use defaults #}
{% set articles = get_article_feed() %}

{# Full notation #}
{% set articles = get_recent_article_feed({ limit: 100 }) %}
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

Step 4: Options reference
-------------------------
| Option        | Type                      | Default       |
| ------------: |---------------------------|---------------|
| page          | int                       | 1             | 
| limit         | int                       | 100           | 
| category      | HasNodeInterface<br>Category| null | 
| tags          | string<br>array<string><br>Taggable | null  |  
| tags_logic    | enum(any,all,few)         | 'any'         | 
| exclude       | array<nodeId>             | null          | 
| feed          | enum(recent,random,...)   | 'recent'      | 


Step 5: Defining custom feed types
----------------------------------

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

####  3. Use custom feed

```jinja
{% set articlesByTitle = get_title_article_feed() %}
```
