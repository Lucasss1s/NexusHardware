<?php
require_once __DIR__ . '/../config/bootstrap.php';

require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/OrderDetail.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Review.php';

/* Auth */
if (!isset($_SESSION['user'])) {
    header("Location: " . BASE_URL . "views/login_register.php");
    exit;
}

$userId = $_SESSION['user']['id'];

/* orders */
$orders = Order::getByUserId($conn, $userId);

/*
 Normalizamos los datos para la vista:
 $ordersData = [
   [
     'order' => Order,
     'details' => [
        [
          'detail' => OrderDetail,
          'product' => [...],
          'review' => Review|null
        ]
     ]
   ]
 ]
*/
$ordersData = [];

foreach ($orders as $order) {
    $details = OrderDetail::getByOrderId($conn, $order->getId());
    $detailsData = [];

    foreach ($details as $detail) {

        // Product
        $stmt = $conn->prepare(
            "SELECT id, name, img FROM product WHERE id = :id"
        );
        $stmt->execute([':id' => $detail->getProductId()]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        // Review (solo si está completada)
        $existingReview = null;
        if ($order->getStatus() === 'Completed') {
            $reviewStmt = $conn->prepare(
                "SELECT * FROM review 
                WHERE user_id = :user 
                AND product_id = :product 
                LIMIT 1"
            );
            $reviewStmt->execute([
                ':user' => $userId,
                ':product' => $detail->getProductId()
            ]);
            $row = $reviewStmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $existingReview = new Review(
                    $row['id'],
                    $row['product_id'],
                    $row['user_id'],
                    $row['rating'],
                    $row['comment'],
                    $row['created_at']
                );
            }
        }

        $detailsData[] = [
            'detail' => $detail,
            'product' => $product,
            'review' => $existingReview
        ];
    }

    $ordersData[] = [
        'order' => $order,
        'details' => $detailsData
    ];
}
