<?php

namespace iutnc\goodfood\action;

use iutnc\goodfood\database\GoodfoodDatabase;

class UpdateOrderTotalAction extends Action
{
    public function execute(): string
    {
        $html = '';
        if ($this->http_method == 'GET') {
            $db = GoodfoodDatabase::getInstance();
            $orders = $db->getOrderNumbers();
            $html = '<h2>Update Order Total</h2>';
            $html .= '<h3>Order Numbers:</h3><ul>';
            foreach ($orders as $order) {
                $html .= '<li>' . $order['numcom'] . '</li>';
            }
            $html .= '</ul>';
            $html .= <<<HTML
                <form method="post" action="?action=updateOrderTotal">
                    <label>Order Number:
                    <input type="number" name="numCom" placeholder="1" required></label><br>
                    <button type="submit">Update Total</button>
                </form>
            HTML;
        } elseif ($this->http_method == 'POST') {
            $numCom = !empty($_POST['numCom']) ? (int)$_POST['numCom'] : 0;
            if ($numCom > 0) {
                $db = GoodfoodDatabase::getInstance();
                $db->updateOrderTotal($numCom);
                $html = '<p>Order total updated successfully for order number ' . $numCom . '.</p>';
            } else {
                $html = '<p>Invalid order number.</p>';
            }
        }
        return $html;
    }
}