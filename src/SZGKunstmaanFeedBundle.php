<?php

namespace SZG\FeedBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use SZG\FeedBundle\Feed\ElasticSearch\CompilerPass\FeedCompilerPass;

class SZGKunstmaanFeedBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new FeedCompilerPass());
    }

}
