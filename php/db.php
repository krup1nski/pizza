<?php

session_start();
require "connection.php";

// для удобного чтения
function tt($value)
{
    echo '<pre>';
    print_r($value);
    echo '</pre>';
}


function select_all($table, $params=[]){
    global $pdo;
    $sql = "SELECT * FROM $table";

    if(!empty($params)){
        $i=0;
        foreach($params as $key=>$value){
            if(!is_numeric($value)){
                $value = "'".$value."'";
            }
            if($i==0){
                $sql .= " WHERE $key=$value";
            }else{
                $sql .= " AND $key=$value";
            }
            $i++;
        }
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

function select_all_filter($table, $filter) {
    global $pdo;

    // Проверяем допустимые значения сортировки, чтобы избежать SQL-инъекций
    $allowedFilters = [
        "name" => "ORDER BY name",
        "asc_price" => "ORDER BY price ASC",
        "desc_price" => "ORDER BY price DESC"
    ];

    $sql = "SELECT * FROM `$table`";

    // Добавляем сортировку, если передан корректный фильтр
    if (array_key_exists($filter, $allowedFilters)) {
        $sql .= " " . $allowedFilters[$filter];
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}


function select_one($table, $id){
    global $pdo;
    $sql = "SELECT * FROM $table WHERE id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch();
}

function selectDoughs($pizzaId) {
    global $pdo;
    $sql = "SELECT 
                d.name AS dough_name
            FROM 
                pizzas p
            JOIN 
                `pizzas-doughs` pd ON p.id = pd.id_pizza
            JOIN 
                doughs d ON pd.id_dough = d.id
            WHERE 
                p.id = :pizzaId";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':pizzaId', $pizzaId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function selectSizes($pizzaId) {
    global $pdo;
    $sql = "SELECT 
                sz.name AS size_name,
                sz.price AS size_price,
                sz.gramm AS gramm_price
            FROM 
                pizzas p
            JOIN 
                `pizzas-sizes` ps ON p.id = ps.id_pizza
            JOIN 
                sizes sz ON ps.id_size = sz.id
            WHERE 
                p.id = :pizzaId";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':pizzaId', $pizzaId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function selectSides($pizzaId) {
    global $pdo;
    $sql = "SELECT 
                sd.name AS side_name,
                sd.price AS side_price
            FROM 
                pizzas p
            JOIN 
                `pizzas-sides` psd ON p.id = psd.id_pizza
            JOIN 
                sides sd ON psd.id_side = sd.id
            WHERE 
                p.id = :pizzaId";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':pizzaId', $pizzaId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function select_orders($phone){
    global $pdo;
    $sql = "SELECT * 
        FROM orders AS o
        JOIN order_items AS oi ON o.id = oi.order_id
        WHERE o.phone = :phone";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);  // Bind the parameter
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);

}

//Pagination
function pag($table, $limit, $offset, $sort_by = null)
{
    global $pdo;

    // Allowed sorting filters
    $allowedFilters = [
        "name" => "ORDER BY name",
        "asc_price" => "ORDER BY price ASC",
        "desc_price" => "ORDER BY price DESC"
    ];

    // Base SQL query
    $sql = "SELECT * FROM {$table} WHERE publish = 1";

    // Apply sorting if provided
    if ($sort_by && array_key_exists($sort_by, $allowedFilters)) {
        $sql .= " " . $allowedFilters[$sort_by];
    }

    // Add pagination
    $sql .= " LIMIT :limit OFFSET :offset";

    $query = $pdo->prepare($sql);
    $query->bindParam(':limit', $limit, PDO::PARAM_INT);
    $query->bindParam(':offset', $offset, PDO::PARAM_INT);
    $query->execute();

    return $query->fetchAll(PDO::FETCH_ASSOC);
}



