<?php

/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\SeoBundle\Event;

use Sonata\BlockBundle\Block\BlockServiceInterface;
use Sonata\BlockBundle\Model\Block;
use Sonata\BlockBundle\Event\BlockEvent;

/**
 * BreadcrumbListener for Block Event.
 *
 * @author Sylvain Deloux <sylvain.deloux@fullsix.com>
 */
class BreadcrumbListener
{
    /**
     * @var array
     */
    protected $blockServices = array();

    /**
     * Add a renderer to the status services list
     *
     * @param BlockServiceInterface $blockService
     */
    public function addBlockService(BlockServiceInterface $blockService)
    {
        $this->blockServices[] = $blockService;
    }

    /**
     * Add context related BlockService, if found.
     *
     * @param BlockEvent $event
     */
    public function onBlock(BlockEvent $event)
    {
        $context = $event->getSetting('context', null);

        foreach ($this->blockServices as $blockService) {
            if ($blockService->handleContext($context)) {
                $block = new Block();
                $block->setId(uniqid());
                $block->setSettings($event->getSettings());
                $block->setType($blockService->getName());

                $event->addBlock($block);

                return;
            }
        }
    }
}
