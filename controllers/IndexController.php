<?php

declare(strict_types=1);

namespace FwTest\Controller;

class IndexController extends AbstractController
{
    /**
     * @Route('/index')
     * @Route(
     *     path="/index",
     *     name="get_index",
     *     methods={"GET"},
     * )
     */
    public function index()
    {
        echo 'Index.';
    }
}