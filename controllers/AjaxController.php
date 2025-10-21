<?php

declare(strict_types=1);

namespace FwTest\Controller;

use FwTest\Classes\ProductDAO;

class AjaxController extends AbstractController
{
    /**
     * @Route('/ajax_delete_product')
     */
    public function deleteProduct()
    {
    	$db = $this->getDatabaseConnection();

        $id = (isset($_POST['id']) && !empty($_POST['id'])) ? $_POST['id'] : 0;

        $return['success'] = false;

        if ($id > 0) {
            $product = new ProductDAO($db, $id);
            $deleted = $product->delete($id);
            $return['success'] = $deleted;
        }

        echo json_encode($return);
        exit;
    }
}