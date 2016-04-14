<?php

namespace SZG\KunstmaanFeedBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use SZG\KunstmaanFeedBundle\DependencyInjection\CompilerPass\FeedCompilerPass;

class KunstmaanFeedBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new FeedCompilerPass());
    }

}
